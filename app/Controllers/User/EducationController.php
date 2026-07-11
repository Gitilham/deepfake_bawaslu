<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\EducationContentModel;

class EducationController extends BaseController
{
    protected EducationContentModel $educationModel;

    public function __construct()
    {
        $this->educationModel = new EducationContentModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Edukasi Deepfake',
            'contents' => $this->educationModel->getActiveContents(),
        ];

        return view('user/education/index', $data);
    }
}