<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VideoDetectionModel;
use App\Models\DetectionFrameModel;
use App\Libraries\DetectionWorkflowService;
use Config\DeepfakeApi;
use Throwable;

class DetectionController extends BaseController
{
    protected VideoDetectionModel $detectionModel;
    protected DetectionFrameModel $frameModel;
    private DeepfakeApi $apiConfig;

    public function __construct()
    {
        $this->detectionModel = new VideoDetectionModel();
        $this->frameModel     = new DetectionFrameModel();
        $this->apiConfig = config('DeepfakeApi');
    }

    public function create()
    {
        return view('user/detections/create', [
            'title' => 'Deteksi Video',
            'layout' => 'layouts/admin',
            'formAction' => 'admin/detections/store',
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

            $payload = [
                'label' => $result['label'],
                'confidence' => $result['confidence'],
                'real_score' => $result['real_score'],
                'fake_score' => $result['fake_score'],
                'detail_url' => base_url('admin/detections/detail/' . $result['detection_id']),
                'filename' => $result['filename'],
                'request_id' => $result['request_id'],
            ];

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'result' => $payload, 'csrf_hash' => csrf_hash()]);
            }

            return redirect()->to('/admin/detections/create')->with('detection_result', $payload);
        } catch (Throwable $exception) {
            log_message('error', 'Admin detection request failed: ' . $exception::class);
            return $this->failure('Terjadi kesalahan saat memproses video. Silakan coba kembali.', [], 500);
        }
    }

    private function uploadRules(): array
    {
        return ['video' => ['label' => 'Video', 'rules' => 'uploaded[video]'
            . '|max_size[video,' . ($this->apiConfig->maxVideoMb * 1024) . ']'
            . '|ext_in[video,' . implode(',', $this->apiConfig->allowedFormats) . ']'
            . '|mime_in[video,video/mp4,video/x-msvideo,video/quicktime,video/x-matroska,video/webm,application/octet-stream]']];
    }

    private function failure(string $message, array $errors, int $status)
    {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode($status)->setJSON(['success' => false, 'message' => $message, 'errors' => $errors, 'csrf_hash' => csrf_hash()]);
        }

        $redirect = redirect()->back()->withInput();
        return $errors !== [] ? $redirect->with('errors', $errors) : $redirect->with('error', $message);
    }

    public function index()
    {
        $filters = [
            'start_date'      => $this->request->getGet('start_date'),
            'end_date'        => $this->request->getGet('end_date'),
            'predicted_label' => $this->request->getGet('predicted_label'),
            'status'          => $this->request->getGet('status'),
        ];

        $data = [
            'title'      => 'Data Deteksi Video',
            'detections' => $this->detectionModel->paginateWithUser($filters, 20),
            'pager'      => $this->detectionModel->pager,
            'filters'    => $filters,
        ];

        return view('admin/detections/index', $data);
    }

    public function detail(int $id)
    {
        $detection = $this->detectionModel->getDetailWithUser($id);

        if (! $detection) {
            return redirect()
                ->to('/admin/detections')
                ->with('error', 'Data deteksi tidak ditemukan.');
        }

        $data = [
            'title'     => 'Detail Deteksi Video',
            'detection' => $detection,
            'frames'    => $this->frameModel->getByDetection($id),
            'layout'    => 'layouts/admin',
            'otherDetectionUrl' => base_url('admin/detections/create'),
            'backUrl'   => base_url('admin/detections'),
            'backLabel' => 'Kembali ke Data Deteksi',
            'reviewUrl' => base_url('admin/detections/review/' . $id),
        ];

        return view('user/history/detail', $data);
    }

    public function markReviewed(int $id)
    {
        $detection = $this->detectionModel->select('id, status, review_status')->find($id);

        if (! $detection) {
            return redirect()
                ->to('/admin/detections')
                ->with('error', 'Data deteksi tidak ditemukan.');
        }

        if (($detection['status'] ?? null) !== 'completed') {
            return redirect()->to('/admin/detections/detail/' . $id)
                ->with('error', 'Hanya deteksi yang selesai dapat ditandai reviewed.');
        }

        $this->detectionModel->update($id, [
            'review_status' => 'reviewed',
            'reviewed_by' => session()->get('user_id'),
            'reviewed_at' => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to('/admin/detections/detail/' . $id)
            ->with('success', 'Data deteksi berhasil ditandai sebagai reviewed.');
    }
}
