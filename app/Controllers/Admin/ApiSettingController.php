<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SystemSettingModel;
use App\Libraries\FlaskApiService;

class ApiSettingController extends BaseController
{
    protected SystemSettingModel $settingModel;

    public function __construct()
    {
        $this->settingModel = new SystemSettingModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Konfigurasi Flask API',
            'settings' => $this->settingModel->getAllKeyValue(),
        ];

        return view('admin/api_settings/index', $data);
    }

    public function update()
    {
        $rules = [
            'flask_api_base_url' => [
                'label' => 'Base URL Flask API',
                'rules' => 'required|max_length[255]',
            ],
            'flask_api_predict_endpoint' => [
                'label' => 'Endpoint Prediksi',
                'rules' => 'required|max_length[255]',
            ],
            'max_video_size_mb' => [
                'label' => 'Maksimal ukuran video',
                'rules' => 'required|integer|greater_than[0]',
            ],
            'allowed_video_types' => [
                'label' => 'Format video',
                'rules' => 'required|max_length[100]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->settingModel->setValue(
            'flask_api_base_url',
            trim((string) $this->request->getPost('flask_api_base_url'))
        );

        $this->settingModel->setValue(
            'flask_api_predict_endpoint',
            trim((string) $this->request->getPost('flask_api_predict_endpoint'))
        );

        $this->settingModel->setValue(
            'max_video_size_mb',
            trim((string) $this->request->getPost('max_video_size_mb'))
        );

        $this->settingModel->setValue(
            'allowed_video_types',
            trim((string) $this->request->getPost('allowed_video_types'))
        );

        return redirect()
            ->to('/admin/api-settings')
            ->with('success', 'Konfigurasi Flask API berhasil diperbarui.');
    }

    public function testConnection()
    {
        $service = new FlaskApiService();
        $result  = $service->testConnection();

        if (($result['success'] ?? false) === true) {
            return redirect()
                ->to('/admin/api-settings')
                ->with('success', 'Koneksi Flask API berhasil. HTTP Status: ' . ($result['http_status'] ?? '-'));
        }

        return redirect()
            ->to('/admin/api-settings')
            ->with('error', $result['message'] ?? 'Koneksi Flask API gagal.');
    }
}