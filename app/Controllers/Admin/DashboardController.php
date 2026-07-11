<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VideoDetectionModel;

class DashboardController extends BaseController
{
    protected UserModel $userModel;
    protected VideoDetectionModel $detectionModel;

    public function __construct()
    {
        $this->userModel      = new UserModel();
        $this->detectionModel = new VideoDetectionModel();
    }

    public function index()
    {
        $totalUsers = $this->userModel
            ->join('roles', 'roles.id = users.role_id')
            ->where('roles.role_name', 'user')
            ->where('users.deleted_at', null)
            ->countAllResults();

        $data = [
            'title'          => 'Dashboard Admin',
            'total_users'    => $totalUsers,
            'total_videos'   => $this->detectionModel->where('deleted_at', null)->countAllResults(),
            'total_deepfake' => $this->detectionModel->countByCondition(['predicted_label' => 'DEEPFAKE']),
            'total_real'     => $this->detectionModel->countByCondition(['predicted_label' => 'REAL']),
            'total_failed'   => $this->detectionModel->countByCondition(['status' => 'failed']),
            'latest_data'    => $this->detectionModel->getAllWithUser(),
        ];

        return view('admin/dashboard', $data);
    }
}