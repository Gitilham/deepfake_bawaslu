<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\VideoDetectionModel;
use App\Models\DetectionFrameModel;
use App\Models\SystemSettingModel;
use App\Libraries\FlaskApiService;
use Throwable;

class DetectionController extends BaseController
{
    protected VideoDetectionModel $detectionModel;
    protected DetectionFrameModel $frameModel;
    protected SystemSettingModel $settingModel;

    public function __construct()
    {
        $this->detectionModel = new VideoDetectionModel();
        $this->frameModel     = new DetectionFrameModel();
        $this->settingModel   = new SystemSettingModel();
    }

    public function create()
    {
        $data = [
            'title'          => 'Deteksi Video Deepfake',
            'max_size_mb'    => $this->settingModel->getValue('max_video_size_mb', '100'),
            'allowed_types'  => $this->settingModel->getValue('allowed_video_types', 'mp4,avi,mov,mkv'),
        ];

        return view('user/detections/create', $data);
    }

    public function store()
    {
        $maxSizeMb    = (int) $this->settingModel->getValue('max_video_size_mb', '100');
        $allowedTypes = (string) $this->settingModel->getValue('allowed_video_types', 'mp4,avi,mov,mkv');

        if ($maxSizeMb <= 0) {
            $maxSizeMb = 100;
        }

        $maxSizeKb = $maxSizeMb * 1024;

        $rules = [
            'video' => [
                'label' => 'Video',
                'rules' => 'uploaded[video]'
                    . '|max_size[video,' . $maxSizeKb . ']'
                    . '|ext_in[video,' . $allowedTypes . ']'
                    . '|mime_in[video,video/mp4,video/x-msvideo,video/quicktime,video/x-matroska,application/octet-stream]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $videoFile = $this->request->getFile('video');

        if (! $videoFile || ! $videoFile->isValid()) {
            return redirect()
                ->back()
                ->with('error', 'File video tidak valid.');
        }

        $uploadPath = WRITEPATH . 'uploads/videos';

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $originalName = $videoFile->getClientName();
        $storedName   = $videoFile->getRandomName();
        $mimeType     = $videoFile->getClientMimeType();
        $fileSize     = $videoFile->getSize();

        $detectionId = null;

        try {
            $videoFile->move($uploadPath, $storedName);

            $relativePath = 'uploads/videos/' . $storedName;
            $absolutePath = $uploadPath . DIRECTORY_SEPARATOR . $storedName;

            $detectionId = $this->detectionModel->insert([
                'user_id'           => session()->get('user_id'),
                'original_filename' => $originalName,
                'stored_filename'   => $storedName,
                'file_path'         => $relativePath,
                'file_mime'         => $mimeType,
                'file_size'         => $fileSize,
                'status'            => 'pending',
                'predicted_label'   => 'UNKNOWN',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ], true);

            $this->detectionModel->update($detectionId, [
                'status'     => 'processing',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            /*
             * Bagian penting:
             * Di sini CodeIgniter 4 mengirim video ke Flask API.
             * Flask API akan mengembalikan hasil prediksi berupa JSON.
             */
            $flaskService = new FlaskApiService();
            $result       = $flaskService->predictVideo($absolutePath, $detectionId);

            $apiResponseJson = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            if (($result['success'] ?? false) === true) {
                $prediction = strtoupper((string) ($result['prediction'] ?? 'UNKNOWN'));

                if (! in_array($prediction, ['REAL', 'DEEPFAKE', 'UNKNOWN'], true)) {
                    $prediction = 'UNKNOWN';
                }

                $this->detectionModel->update($detectionId, [
                    'status'            => 'completed',
                    'predicted_label'   => $prediction,
                    'confidence'        => $result['confidence'] ?? null,
                    'real_score'        => $result['real_score'] ?? null,
                    'fake_score'        => $result['fake_score'] ?? null,
                    'duration_seconds'  => $result['duration_seconds'] ?? null,
                    'api_response_json' => $apiResponseJson,
                    'error_message'     => null,
                    'updated_at'        => date('Y-m-d H:i:s'),
                ]);

                /*
                 * Jika Flask API mengirim detail frame,
                 * maka data frame disimpan ke tabel detection_frames.
                 */
                if (! empty($result['frames']) && is_array($result['frames'])) {
                    foreach ($result['frames'] as $frame) {
                        $label = strtoupper((string) ($frame['label'] ?? 'UNKNOWN'));

                        if (! in_array($label, ['REAL', 'DEEPFAKE', 'UNKNOWN'], true)) {
                            $label = 'UNKNOWN';
                        }

                        $this->frameModel->insert([
                            'detection_id' => $detectionId,
                            'frame_time'   => $frame['frame_time'] ?? null,
                            'label'        => $label,
                            'confidence'   => $frame['confidence'] ?? null,
                            'real_score'   => $frame['real_score'] ?? null,
                            'fake_score'   => $frame['fake_score'] ?? null,
                            'frame_path'   => $frame['frame_path'] ?? null,
                            'created_at'   => date('Y-m-d H:i:s'),
                            'updated_at'   => date('Y-m-d H:i:s'),
                        ]);
                    }
                }

                return redirect()
                    ->to('/user/history/detail/' . $detectionId)
                    ->with('success', 'Video berhasil diproses.');
            }

            $this->detectionModel->update($detectionId, [
                'status'            => 'failed',
                'api_response_json' => $apiResponseJson,
                'error_message'     => $result['message'] ?? 'Prediksi gagal diproses oleh Flask API.',
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);

            return redirect()
                ->to('/user/history/detail/' . $detectionId)
                ->with('error', $result['message'] ?? 'Prediksi gagal diproses oleh Flask API.');
        } catch (Throwable $e) {
            if ($detectionId !== null) {
                $this->detectionModel->update($detectionId, [
                    'status'        => 'failed',
                    'error_message' => $e->getMessage(),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memproses video: ' . $e->getMessage());
        }
    }
}