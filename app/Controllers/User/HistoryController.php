<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\VideoDetectionModel;
use App\Models\DetectionFrameModel;

class HistoryController extends BaseController
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
        $userId = (int) session()->get('user_id');

        $data = [
            'title'      => 'Riwayat Deteksi',
            'detections' => $this->detectionModel->paginateByUser($userId, 15),
            'pager'      => $this->detectionModel->pager,
        ];

        return view('user/history/index', $data);
    }

    public function detail(int $id)
    {
        $userId = (int) session()->get('user_id');

        $detection = $this->detectionModel->getUserDetectionDetail($id, $userId);

        if (! $detection) {
            return redirect()
                ->to('/user/history')
                ->with('error', 'Data deteksi tidak ditemukan atau bukan milik Anda.');
        }

        $data = [
            'title'     => 'Detail Riwayat Deteksi',
            'detection' => $detection,
            'frames'    => $this->frameModel->getByDetection($id),
        ];

        return view('user/history/detail', $data);
    }
}
