<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VideoDetectionModel;
use App\Models\DetectionFrameModel;

class DetectionController extends BaseController
{
    protected VideoDetectionModel $detectionModel;
    protected DetectionFrameModel $frameModel;

    public function __construct()
    {
        $this->detectionModel = new VideoDetectionModel();
        $this->frameModel     = new DetectionFrameModel();
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
            'detections' => $this->detectionModel->getAllWithUser($filters),
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
        ];

        return view('admin/detections/detail', $data);
    }

    public function markReviewed(int $id)
    {
        $detection = $this->detectionModel->find($id);

        if (! $detection) {
            return redirect()
                ->to('/admin/detections')
                ->with('error', 'Data deteksi tidak ditemukan.');
        }

        $this->detectionModel->update($id, [
            'status'      => 'reviewed',
            'reviewed_by' => session()->get('user_id'),
            'reviewed_at' => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to('/admin/detections/detail/' . $id)
            ->with('success', 'Data deteksi berhasil ditandai sebagai reviewed.');
    }
}