<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\VideoDetectionModel;

class DashboardController extends BaseController
{
    protected VideoDetectionModel $detectionModel;

    public function __construct()
    {
        $this->detectionModel = new VideoDetectionModel();
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');

        $data = [
            'title'          => 'Dashboard User',
            'total_videos'   => $this->detectionModel->countByCondition(['user_id' => $userId]),
            'total_real'     => $this->detectionModel->countByCondition(['user_id' => $userId, 'predicted_label' => 'REAL']),
            'total_deepfake' => $this->detectionModel->countByCondition(['user_id' => $userId, 'predicted_label' => 'DEEPFAKE']),
            'total_failed'   => $this->detectionModel->countByCondition(['user_id' => $userId, 'status' => 'failed']),
            'latest_data'    => $this->detectionModel->getByUser($userId),
        ];

        return view('user/dashboard', $data);
    }
}