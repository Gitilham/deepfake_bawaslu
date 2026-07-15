<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SystemSettingModel;
use App\Libraries\DeepfakeApiClient;

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
            'title'    => 'Konfigurasi Backend API',
            'settings' => $this->settingModel->getAllKeyValue(),
        ];

        return view('admin/api_settings/index', $data);
    }

    public function update()
    {
        $rules = [
            'flask_api_base_url' => [
                'label' => 'Base URL Backend API',
                'rules' => 'required|max_length[255]|valid_url_strict[http,https]',
            ],
            'flask_api_predict_endpoint' => [
                'label' => 'Endpoint Prediksi',
                'rules' => 'required|max_length[255]',
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
        $allowedHosts = array_filter(array_map('trim', explode(',', (string) env('ML_API_ALLOWED_HOSTS', ''))));
        $allowAnyHost = in_array('*', $allowedHosts, true);
        if (! is_array($parts) || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])
            || (! $allowAnyHost && $allowedHosts !== [] && ! in_array(strtolower($parts['host']), array_map('strtolower', $allowedHosts), true))) {
            return redirect()->back()->withInput()->with('error', 'URL backend AI tidak valid atau host tidak termasuk allowlist.');
        }
        if (! str_starts_with($endpoint, '/') || preg_match('/[\x00-\x1F\x7F]/', $endpoint)) {
            return redirect()->back()->withInput()->with('error', 'Endpoint prediksi harus diawali garis miring dan tidak boleh memuat karakter kontrol.');
        }
        $maxVideoSize = trim((string) ($this->request->getPost('max_video_size_mb')
            ?? $this->settingModel->getValue('max_video_size_mb', '100')));
        $allowedVideoTypes = (string) ($this->request->getPost('allowed_video_types')
            ?? $this->settingModel->getValue('allowed_video_types', 'mp4,avi,mov,mkv'));

        if (! ctype_digit($maxVideoSize) || (int) $maxVideoSize < 1 || (int) $maxVideoSize > 1024) {
            return redirect()->back()->withInput()->with('error', 'Maksimal ukuran video harus antara 1 sampai 1024 MB.');
        }

        $types = array_values(array_unique(array_intersect(
            array_map(static fn (string $value): string => strtolower(trim($value)), explode(',', $allowedVideoTypes)),
            ['mp4', 'avi', 'mov', 'mkv']
        )));
        if ($types === []) {
            return redirect()->back()->withInput()->with('error', 'Format video tidak valid. Gunakan mp4, avi, mov, atau mkv.');
        }

        $database = db_connect();
        $database->transStart();

        $saved = $this->settingModel->setValue('flask_api_base_url', $baseUrl)
            && $this->settingModel->setValue('flask_api_predict_endpoint', $endpoint)
            && $this->settingModel->setValue('max_video_size_mb', $maxVideoSize)
            && $this->settingModel->setValue('allowed_video_types', implode(',', $types));

        $database->transComplete();

        if (! $saved || ! $database->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Konfigurasi Backend API gagal disimpan. Periksa koneksi database dan coba kembali.');
        }

        return redirect()
            ->to('/admin/api-settings')
            ->with('success', 'Konfigurasi Backend API berhasil diperbarui.');
    }

    public function testConnection()
    {
        // Tes manual selalu memakai URL runtime dari Config\DeepfakeApi.
        // Nilai URL dari browser tidak dipercaya dan tidak diteruskan ke cURL.
        $result = (new DeepfakeApiClient())->testConnection();

        return $this->connectionResponse($result);
    }

    private function connectionResponse(array $result)
    {
        if ($this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(($result['success'] ?? false) ? 200 : 503)
                ->setJSON([
                    'success'     => (bool) ($result['success'] ?? false),
                    'message'     => $result['message'] ?? 'Test koneksi selesai.',
                    'http_status' => $result['http_status'] ?? 0,
                    'latency_ms'  => $result['latency_ms'] ?? null,
                    'csrf_hash'   => csrf_hash(),
                ]);
        }

        if (($result['success'] ?? false) === true) {
            return redirect()
                ->to('/admin/api-settings')
                ->with('success', 'Koneksi Backend API berhasil. HTTP Status: ' . ($result['http_status'] ?? '-'));
        }

        return redirect()
            ->to('/admin/api-settings')
            ->with('error', $result['message'] ?? 'Koneksi Backend API gagal.');
    }
}
