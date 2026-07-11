<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\VideoDetectionModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class VideoController extends BaseController
{
    protected VideoDetectionModel $detectionModel;

    public function __construct()
    {
        $this->detectionModel = new VideoDetectionModel();
    }

    public function play($id)
    {
        $userId = (int) session()->get('user_id');

        $video = $this->detectionModel->getUserDetectionDetail($id, $userId);

        if (!$video) {
            throw PageNotFoundException::forPageNotFound('Video tidak ditemukan.');
        }

        $file = WRITEPATH . $video['file_path'];

        if (!file_exists($file)) {
            throw PageNotFoundException::forPageNotFound('File video tidak ditemukan.');
        }

        return $this->response->download($file, null, true);
    }
}