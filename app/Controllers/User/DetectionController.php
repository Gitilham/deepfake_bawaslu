<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Libraries\DetectionWorkflowService;
use Config\DeepfakeApi;
use Throwable;

class DetectionController extends BaseController
{
    private DeepfakeApi $apiConfig;

    public function __construct()
    {
        $this->apiConfig = config('DeepfakeApi');
    }

    public function create()
    {
        return view('user/detections/create', [
            'title' => 'Deteksi Video Deepfake',
            'max_size_mb' => $this->apiConfig->maxVideoMb,
            'allowed_types' => implode(',', $this->apiConfig->allowedFormats),
        ]);
    }

    public function store()
    {
        if (! $this->validate($this->uploadRules())) {
            return $this->failure('Periksa kembali file video yang dipilih.', $this->validator->getErrors(), 422);
        }

        $video = $this->request->getFile('video');
        if (! $video || ! $video->isValid() || $video->getSize() < 1) {
            return $this->failure('File video tidak valid atau kosong.', [], 422);
        }

        try {
            $result = (new DetectionWorkflowService())->process($video, (int) session()->get('user_id'));
            if (! $result['success']) {
                return $this->failure((string) $result['message'], [], 502);
            }

            $payload = $this->resultPayload($result, base_url('user/history/detail/' . $result['detection_id']));
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'result' => $payload, 'csrf_hash' => csrf_hash()]);
            }

            return redirect()->to('/user/detections/create')->with('detection_result', $payload);
        } catch (Throwable $exception) {
            log_message('error', 'User detection request failed: ' . $exception::class);
            return $this->failure('Terjadi kesalahan saat memproses video. Silakan coba kembali.', [], 500);
        }
    }

    private function uploadRules(): array
    {
        $extensions = implode(',', $this->apiConfig->allowedFormats);

        return ['video' => ['label' => 'Video', 'rules' => 'uploaded[video]'
            . '|max_size[video,' . ($this->apiConfig->maxVideoMb * 1024) . ']'
            . '|ext_in[video,' . $extensions . ']'
            . '|mime_in[video,video/mp4,video/x-msvideo,video/quicktime,video/x-matroska,video/webm,application/octet-stream]']];
    }

    private function resultPayload(array $result, string $detailUrl): array
    {
        return [
            'label' => $result['label'],
            'confidence' => $result['confidence'],
            'real_score' => $result['real_score'],
            'fake_score' => $result['fake_score'],
            'detail_url' => $detailUrl,
            'filename' => $result['filename'],
            'request_id' => $result['request_id'],
        ];
    }

    private function failure(string $message, array $errors, int $status)
    {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode($status)->setJSON(['success' => false, 'message' => $message, 'errors' => $errors, 'csrf_hash' => csrf_hash()]);
        }

        $redirect = redirect()->back()->withInput();
        return $errors !== [] ? $redirect->with('errors', $errors) : $redirect->with('error', $message);
    }
}
