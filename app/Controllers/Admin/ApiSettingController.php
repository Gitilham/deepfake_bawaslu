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
                'rules' => 'required|max_length[255]|valid_url_strict[http,https]',
            ],
            'flask_api_predict_endpoint' => [
                'label' => 'Endpoint Prediksi',
                'rules' => 'required|max_length[255]',
            ],
            'max_video_size_mb' => [
                'label' => 'Maksimal ukuran video',
                'rules' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[1024]',
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

        $baseUrl = rtrim(trim((string) $this->request->getPost('flask_api_base_url')), '/');
        $endpoint = trim((string) $this->request->getPost('flask_api_predict_endpoint'));
        $parts = parse_url($baseUrl);
        $allowedHosts = array_filter(array_map('trim', explode(',', (string) env('ML_API_ALLOWED_HOSTS', '127.0.0.1,localhost,deepfake-api'))));
        if (! is_array($parts) || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])
            || ($allowedHosts !== [] && ! in_array(strtolower($parts['host']), array_map('strtolower', $allowedHosts), true))) {
            return redirect()->back()->withInput()->with('error', 'URL backend AI tidak valid atau host tidak termasuk allowlist.');
        }
        if (! str_starts_with($endpoint, '/') || preg_match('/[\x00-\x1F\x7F]/', $endpoint)) {
            return redirect()->back()->withInput()->with('error', 'Endpoint prediksi harus diawali garis miring dan tidak boleh memuat karakter kontrol.');
        }
        $types = array_values(array_unique(array_intersect(
            array_map(static fn (string $value): string => strtolower(trim($value)), explode(',', (string) $this->request->getPost('allowed_video_types'))),
            ['mp4', 'avi', 'mov', 'mkv']
        )));
        if ($types === []) {
            return redirect()->back()->withInput()->with('error', 'Format video tidak valid. Gunakan mp4, avi, mov, atau mkv.');
        }

        $this->settingModel->setValue(
            'flask_api_base_url',
            $baseUrl
        );

        $this->settingModel->setValue(
            'flask_api_predict_endpoint',
            $endpoint
        );

        $this->settingModel->setValue(
            'max_video_size_mb',
            trim((string) $this->request->getPost('max_video_size_mb'))
        );

        $this->settingModel->setValue(
            'allowed_video_types',
            implode(',', $types)
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
