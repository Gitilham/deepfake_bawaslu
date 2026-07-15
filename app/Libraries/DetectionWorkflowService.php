<?php

namespace App\Libraries;

use App\Models\DetectionFrameModel;
use App\Models\VideoDetectionModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;
use Throwable;

class DetectionWorkflowService
{
    private VideoDetectionModel $detections;
    private DetectionFrameModel $frames;

    public function __construct(
        private ?DeepfakeApiClient $api = null,
        private ?DeepfakeResponseNormalizer $normalizer = null
    ) {
        $this->api ??= new DeepfakeApiClient();
        $this->normalizer ??= new DeepfakeResponseNormalizer();
        $this->detections = new VideoDetectionModel();
        $this->frames = new DetectionFrameModel();
    }

    /**
     * Mengirim temporary upload langsung ke backend, lalu memindahkannya satu
     * kali ke penyimpanan permanen hanya setelah prediksi berhasil.
     */
    public function process(UploadedFile $video, int $userId): array
    {
        $totalStarted = hrtime(true);
        $validationStarted = hrtime(true);
        $temporaryPath = $video->getTempName();
        $originalName = basename($video->getClientName());
        $mimeType = (string) $video->getMimeType();
        $fileSize = (int) $video->getSize();
        $requestId = bin2hex(random_bytes(16));
        $validationSeconds = $this->secondsSince($validationStarted);
        $storedPath = null;

        if (! is_file($temporaryPath) || ! is_readable($temporaryPath) || $fileSize < 1) {
            throw new RuntimeException('File upload sementara tidak dapat dibaca.');
        }

        try {
            $apiResult = $this->api->predictVideo($temporaryPath, $originalName, $mimeType, $requestId);
            $normalized = $this->normalizer->normalize($apiResult);

            if (! $normalized['success']) {
                $this->writeTimingLog($requestId, $fileSize, $mimeType, $validationSeconds, $apiResult, null, $totalStarted, 'failed');

                return [
                    'success' => false,
                    'message' => $apiResult['message'] ?? 'Prediksi gagal diproses oleh Backend API.',
                    'request_id' => $requestId,
                ];
            }

            $uploadPath = WRITEPATH . 'uploads/videos';
            if (! is_dir($uploadPath) && ! mkdir($uploadPath, 0775, true) && ! is_dir($uploadPath)) {
                throw new RuntimeException('Folder penyimpanan video tidak dapat dibuat.');
            }

            $storedName = $video->getRandomName();
            $video->move($uploadPath, $storedName);
            $storedPath = $uploadPath . DIRECTORY_SEPARATOR . $storedName;
            $relativePath = 'uploads/videos/' . $storedName;

            $databaseStarted = hrtime(true);
            $database = db_connect();
            $database->transBegin();

            $detectionId = $this->detections->insert([
                'user_id' => $userId,
                'original_filename' => $originalName,
                'stored_filename' => $storedName,
                'file_path' => $relativePath,
                'file_mime' => $mimeType,
                'file_size' => $fileSize,
                'status' => 'completed',
                'predicted_label' => $normalized['final_decision'],
                'binary_prediction' => $normalized['prediction'],
                'confidence' => $normalized['confidence'],
                'real_score' => $normalized['real_score'],
                'fake_score' => $normalized['fake_score'],
                'duration_seconds' => $normalized['processing_seconds'],
                'request_id' => $apiResult['request_id'] ?? $requestId,
                'model_version' => $normalized['model_version'],
                'frames_used' => $normalized['frames_used'],
                'face_detected_count' => $normalized['face_detected_count'],
                'api_latency_ms' => isset($apiResult['api_seconds']) ? round((float) $apiResult['api_seconds'] * 1000, 3) : null,
                'api_response_json' => $apiResult['raw_response'] ?? json_encode($normalized['backend'], JSON_UNESCAPED_SLASHES),
                'error_message' => null,
            ], true);

            if (! $detectionId) {
                throw new RuntimeException('Hasil deteksi tidak dapat disimpan.');
            }

            foreach ($normalized['frames'] as $frame) {
                $label = strtoupper((string) ($frame['label'] ?? 'UNKNOWN'));
                if (! in_array($label, ['REAL', 'DEEPFAKE', 'UNKNOWN'], true)) {
                    $label = 'UNKNOWN';
                }
                $this->frames->insert([
                    'detection_id' => $detectionId,
                    'frame_time' => $frame['frame_time'] ?? null,
                    'label' => $label,
                    'confidence' => $frame['confidence'] ?? null,
                    'real_score' => $frame['real_score'] ?? null,
                    'fake_score' => $frame['fake_score'] ?? null,
                    'frame_path' => $frame['frame_path'] ?? null,
                ]);
            }

            if ($database->transStatus() === false) {
                throw new RuntimeException('Transaksi penyimpanan hasil deteksi gagal.');
            }
            $database->transCommit();
            $databaseSeconds = $this->secondsSince($databaseStarted);
            $this->writeTimingLog($requestId, $fileSize, $mimeType, $validationSeconds, $apiResult, $databaseSeconds, $totalStarted, 'completed');

            return [
                'success' => true,
                'detection_id' => (int) $detectionId,
                'request_id' => $apiResult['request_id'] ?? $requestId,
                'filename' => $originalName,
                'label' => $normalized['final_decision'],
                'confidence' => $normalized['confidence'],
                'real_score' => $normalized['real_score'],
                'fake_score' => $normalized['fake_score'],
            ];
        } catch (Throwable $exception) {
            if (isset($database)) {
                $database->transRollback();
            }
            if ($storedPath !== null && is_file($storedPath)) {
                @unlink($storedPath);
            }
            log_message('error', 'Detection workflow failed: ' . json_encode([
                'request_id' => $requestId,
                'status' => 'failed',
                'error_type' => $exception::class,
            ], JSON_UNESCAPED_SLASHES));
            throw $exception;
        }
    }

    private function secondsSince(int $started): float
    {
        return (hrtime(true) - $started) / 1e9;
    }

    private function writeTimingLog(string $requestId, int $size, string $mime, float $validation, array $api, ?float $database, int $totalStarted, string $status): void
    {
        log_message('info', 'Detection timing: ' . json_encode([
            'request_id' => $requestId,
            'file_size_bytes' => $size,
            'mime_type' => $mime,
            'frontend_validation_seconds' => round($validation, 6),
            'frontend_to_backend_seconds' => isset($api['api_seconds']) ? round((float) $api['api_seconds'], 6) : null,
            'backend_processing_seconds' => isset($api['processing_seconds']) ? (float) $api['processing_seconds'] : null,
            'database_save_seconds' => $database === null ? null : round($database, 6),
            'total_request_seconds' => round($this->secondsSince($totalStarted), 6),
            'backend_http_status' => $api['http_status'] ?? null,
            'status' => $status,
        ], JSON_UNESCAPED_SLASHES));
    }
}
