<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Libraries\FlaskApiService;
use App\Models\DetectionFrameModel;
use App\Models\SystemSettingModel;
use App\Models\VideoDetectionModel;
use Throwable;

class DetectionController extends BaseController
{
    protected VideoDetectionModel $detectionModel;
    protected DetectionFrameModel $frameModel;
    protected SystemSettingModel $settingModel;

    public function __construct()
    {
        $this->detectionModel = new VideoDetectionModel();
        $this->frameModel = new DetectionFrameModel();
        $this->settingModel = new SystemSettingModel();
    }

    public function create()
    {
        $types = $this->settingModel->getAllowedVideoTypes();
        $maxSize = $this->settingModel->getInt('max_video_size_mb', 100, 1, 1024);

        try {
            $health = (new FlaskApiService($this->settingModel))->checkHealth();
        } catch (Throwable $exception) {
            log_message('warning', 'Health check backend AI gagal: {message}', ['message' => $exception->getMessage()]);
            $health = ['success' => false, 'status' => 'connection_error', 'message' => 'Model Tidak Tersedia'];
        }

        return view('user/detections/create', [
            'title' => 'Deteksi Video Deepfake',
            'max_size_mb' => $maxSize,
            'allowed_types' => implode(',', $types),
            'maxVideoSizeMb' => $maxSize,
            'maxVideoDuration' => $this->settingModel->getInt('max_video_upload_duration', 0, 0, 86400),
            'allowedVideoExtensions' => $types,
            'modelReady' => ($health['success'] ?? false) === true,
            'modelStatus' => $health['status'] ?? 'connection_error',
            'modelStatusMessage' => ($health['success'] ?? false) === true ? 'Model Siap' : 'Model Tidak Tersedia',
        ]);
    }

    public function store()
    {
        try {
            $health = (new FlaskApiService($this->settingModel))->checkHealth();
            if (! ($health['success'] ?? false)) {
                return redirect()->back()->with('error', 'Model Tidak Tersedia. Silakan coba kembali beberapa saat lagi.');
            }
        } catch (Throwable $exception) {
            log_message('warning', 'Upload dibatalkan karena health check gagal: {message}', ['message' => $exception->getMessage()]);
            return redirect()->back()->with('error', 'Model Tidak Tersedia. Silakan coba kembali beberapa saat lagi.');
        }

        $maxSizeMb = $this->settingModel->getInt('max_video_size_mb', 100, 1, 1024);
        $allowedTypes = $this->settingModel->getAllowedVideoTypes();
        $rules = ['video' => [
            'label' => 'Video',
            'rules' => 'uploaded[video]|max_size[video,' . ($maxSizeMb * 1024) . ']'
                . '|ext_in[video,' . implode(',', $allowedTypes) . ']'
                . '|mime_in[video,video/mp4,video/x-msvideo,video/quicktime,video/x-matroska,application/octet-stream]',
        ]];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tempPath = null;
        $storedPath = null;
        $detectionId = null;
        $redirect = '/user/history';

        try {
            $videoFile = $this->request->getFile('video');
            if (! $videoFile || ! $videoFile->isValid()) {
                throw new \RuntimeException('File video tidak valid.');
            }

            $tempPath = $videoFile->getTempName();
            if ($tempPath === '' || ! is_file($tempPath) || ! is_readable($tempPath) || filesize($tempPath) <= 0) {
                throw new \RuntimeException('File video sementara tidak dapat dibaca.');
            }

            $originalName = basename($videoFile->getClientName());
            $storeRaw = $this->settingModel->getBool('store_raw_video', filter_var(env('STORE_RAW_VIDEO', 'false'), FILTER_VALIDATE_BOOLEAN));
            $storedName = null;
            $relativePath = null;
            $apiPath = $tempPath;

            if ($storeRaw) {
                $directory = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'videos';
                if (! is_dir($directory) && ! mkdir($directory, 0750, true) && ! is_dir($directory)) {
                    throw new \RuntimeException('Penyimpanan audit video tidak tersedia.');
                }
                $storedName = bin2hex(random_bytes(16)) . '.' . strtolower($videoFile->getExtension());
                $storedPath = $directory . DIRECTORY_SEPARATOR . $storedName;
                if (! copy($tempPath, $storedPath)) {
                    throw new \RuntimeException('Video audit tidak dapat disimpan.');
                }
                $relativePath = 'uploads/videos/' . $storedName;
                $apiPath = $storedPath;
            }

            $detectionId = $this->detectionModel->insert([
                'user_id' => (int) session()->get('user_id'),
                'original_filename' => $originalName,
                'stored_filename' => $storedName,
                'file_path' => $relativePath,
                'file_mime' => $videoFile->getClientMimeType(),
                'file_size' => $videoFile->getSize(),
                'status' => 'pending',
                'predicted_label' => 'UNKNOWN',
                'review_status' => 'unreviewed',
            ], true);
            $redirect = '/user/history/detail/' . $detectionId;
            $this->detectionModel->update($detectionId, ['status' => 'processing']);

            $service = new FlaskApiService($this->settingModel);
            $result = $service->predictVideo($apiPath, [
                'detection_id' => $detectionId,
                'original_filename' => $originalName,
            ]);

            if (! $result['success']) {
                $this->detectionModel->update($detectionId, [
                    'status' => 'failed',
                    'predicted_label' => 'UNKNOWN',
                    'api_latency_ms' => $result['latency_ms'],
                    'error_message' => $service->extractErrorMessage($result),
                    'api_response_json' => json_encode($service->buildCompactLog($result), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
                return redirect()->to($redirect)
                    ->with('error', $service->extractErrorMessage($result))
                    ->with('show_result_alert', true);
            }

            $compact = [
                'request_id' => $result['request_id'],
                'result_status' => $result['result_status'],
                'binary_prediction' => $result['binary_prediction'],
                'requires_manual_review' => $result['requires_manual_review'],
                'model_version' => $result['model_version'],
                'confidence_note' => $result['confidence_note'],
                'decision_rule' => $result['decision_rule'],
                'decision_explanation' => $result['decision_explanation'],
            ];
            $finalData = [
                'status' => 'completed',
                'predicted_label' => $result['result_status'],
                'request_id' => $result['request_id'],
                'binary_prediction' => $result['binary_prediction'],
                'requires_manual_review' => $result['requires_manual_review'] ? 1 : 0,
                'review_status' => $result['requires_manual_review'] ? 'needs_review' : 'unreviewed',
                'confidence' => $result['confidence'],
                'real_score' => $result['real_score'],
                'fake_score' => $result['fake_score'],
                'duration_seconds' => $result['duration_seconds'],
                'model_version' => $result['model_version'],
                'threshold' => $result['threshold'],
                'margin' => $result['margin'],
                'confidence_note' => $result['confidence_note'],
                'decision_rule' => $result['decision_rule'],
                'decision_explanation' => $result['decision_explanation'],
                'frames_used' => $result['frames_used'],
                'face_detected_count' => $result['face_detected_count'],
                'min_face_frames' => $result['min_face_frames'],
                'api_latency_ms' => $result['latency_ms'],
                'api_response_json' => json_encode($compact, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'error_message' => null,
            ];

            $db = db_connect();
            $db->transStart();
            $this->detectionModel->update($detectionId, $finalData);
            if ($this->settingModel->getBool('store_frame_metadata', filter_var(env('STORE_FRAME_METADATA', 'false'), FILTER_VALIDATE_BOOLEAN))) {
                $rows = $this->buildFrameRows($detectionId, $result['frames']);
                if ($rows !== []) {
                    $this->frameModel->insertBatch($rows, 100);
                }
            }
            $db->transComplete();
            if (! $db->transStatus()) {
                throw new \RuntimeException('Hasil prediksi tidak dapat disimpan secara utuh.');
            }

            log_message('info', 'Deteksi selesai detection_id={detection_id} request_id={request_id} user_id={user_id} http={http} latency={latency} result={result}', [
                'detection_id' => $detectionId,
                'request_id' => $result['request_id'] ?? '-',
                'user_id' => (int) session()->get('user_id'),
                'http' => $result['http_status'],
                'latency' => $result['latency_ms'],
                'result' => $result['result_status'],
            ]);

            return redirect()->to($redirect)
                ->with('success', 'Video berhasil diproses.')
                ->with('show_result_alert', true);
        } catch (Throwable $exception) {
            if ($detectionId !== null) {
                $this->detectionModel->update($detectionId, [
                    'status' => 'failed',
                    'predicted_label' => 'UNKNOWN',
                    'error_message' => mb_substr($exception->getMessage(), 0, 500),
                ]);
            }
            log_message('error', 'Proses deteksi gagal detection_id={detection_id}: {message}', [
                'detection_id' => $detectionId ?? '-',
                'message' => $exception->getMessage(),
            ]);
            $response = redirect()->to($redirect)->with('error', 'Terjadi kesalahan saat memproses video.');
            if ($detectionId !== null) {
                $response->with('show_result_alert', true);
            }
            return $response;
        } finally {
            if ($tempPath !== null && is_file($tempPath)) {
                @unlink($tempPath);
            }
            if ($storedPath !== null && is_file($storedPath) && ! $this->settingModel->getBool('store_raw_video', false)) {
                @unlink($storedPath);
            }
        }
    }

    private function buildFrameRows(int $detectionId, array $frames): array
    {
        $now = date('Y-m-d H:i:s');
        $rows = [];
        foreach ($frames as $frame) {
            if (! is_array($frame)) {
                continue;
            }
            $rows[] = [
                'detection_id' => $detectionId,
                'frame_time' => is_numeric($frame['frame_time'] ?? null) ? (float) $frame['frame_time'] : null,
                'source_frame_index' => is_numeric($frame['source_frame_index'] ?? null) ? (int) $frame['source_frame_index'] : null,
                'face_detected' => filter_var($frame['face_detected'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                'face_confidence' => is_numeric($frame['face_confidence'] ?? null) ? (float) $frame['face_confidence'] : null,
                'crop_method' => isset($frame['crop_method']) ? mb_substr((string) $frame['crop_method'], 0, 50) : null,
                'repeated_frame' => filter_var($frame['repeated_frame'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                'bbox_json' => isset($frame['bbox']) ? json_encode(['bbox' => $frame['bbox'], 'bbox_padded' => $frame['bbox_padded'] ?? null]) : null,
                'frame_status' => isset($frame['status']) ? mb_substr((string) $frame['status'], 0, 50) : null,
                'note' => isset($frame['note']) ? mb_substr((string) $frame['note'], 0, 500) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        return $rows;
    }
}
