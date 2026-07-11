<?php

namespace App\Libraries;

use App\Models\SystemSettingModel;
use App\Models\FlaskApiLogModel;
use CURLFile;

class FlaskApiService
{
    protected SystemSettingModel $settingModel;
    protected FlaskApiLogModel $logModel;

    public function __construct()
    {
        $this->settingModel = new SystemSettingModel();
        $this->logModel     = new FlaskApiLogModel();
    }

    /**
     * Ambil base URL Flask API dari tabel system_settings.
     */
    public function getBaseUrl(): string
    {
        return rtrim($this->settingModel->getValue('flask_api_base_url', 'http://127.0.0.1:5000'), '/');
    }

    /**
     * Ambil endpoint prediksi Flask API dari tabel system_settings.
     */
    public function getPredictEndpoint(): string
    {
        $endpoint = $this->settingModel->getValue('flask_api_predict_endpoint', '/predict-video');

        if (! str_starts_with($endpoint, '/')) {
            $endpoint = '/' . $endpoint;
        }

        return $endpoint;
    }

    /**
     * URL lengkap endpoint prediksi.
     */
    public function getPredictUrl(): string
    {
        return $this->getBaseUrl() . $this->getPredictEndpoint();
    }

    /**
     * Test koneksi sederhana ke base URL Flask API.
     * Catatan:
     * Flask kamu sebaiknya punya route GET / atau /health agar test ini valid.
     */
    public function testConnection(): array
    {
        $url = $this->getBaseUrl();
        $start = microtime(true);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $latency  = (int) ((microtime(true) - $start) * 1000);

        curl_close($ch);

        $this->logModel->insert([
            'detection_id'      => null,
            'endpoint'          => $url,
            'request_method'    => 'GET',
            'http_status'       => $httpCode,
            'request_payload'   => null,
            'response_payload'  => $response,
            'latency_ms'        => $latency,
            'error_message'     => $error ?: null,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        if ($error) {
            return [
                'success' => false,
                'message' => 'Gagal terhubung ke Flask API: ' . $error,
                'http_status' => $httpCode,
                'latency_ms' => $latency,
            ];
        }

        return [
            'success' => $httpCode >= 200 && $httpCode < 500,
            'message' => 'Koneksi Flask API berhasil dicek.',
            'http_status' => $httpCode,
            'latency_ms' => $latency,
            'response' => $response,
        ];
    }

    /**
     * Kirim video ke Flask API untuk prediksi deepfake.
     *
     * @param string $videoPath Path absolut file video di server.
     * @param int|null $detectionId ID data video_detections.
     */
    public function predictVideo(string $videoPath, ?int $detectionId = null): array
    {
        $url = $this->getPredictUrl();

        if (! file_exists($videoPath)) {
            return [
                'success' => false,
                'message' => 'File video tidak ditemukan di server.',
            ];
        }

        $start = microtime(true);

        $mimeType = mime_content_type($videoPath) ?: 'application/octet-stream';

        $postFields = [
            'video' => new CURLFile($videoPath, $mimeType, basename($videoPath)),
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 300,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
            ],
        ]);

        $responseBody = curl_exec($ch);
        $curlError    = curl_error($ch);
        $httpCode     = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $latency      = (int) ((microtime(true) - $start) * 1000);

        curl_close($ch);

        // Simpan log komunikasi CodeIgniter 4 ke Flask API.
        $this->logModel->insert([
            'detection_id'      => $detectionId,
            'endpoint'          => $url,
            'request_method'    => 'POST',
            'http_status'       => $httpCode,
            'request_payload'   => json_encode([
                'video_filename' => basename($videoPath),
                'mime_type'      => $mimeType,
            ]),
            'response_payload'  => $responseBody,
            'latency_ms'        => $latency,
            'error_message'     => $curlError ?: null,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        if ($curlError) {
            return [
                'success' => false,
                'message' => 'Gagal menghubungi Flask API: ' . $curlError,
                'http_status' => $httpCode,
                'raw_response' => $responseBody,
            ];
        }

        $json = json_decode($responseBody, true);

        if (! is_array($json)) {
            return [
                'success' => false,
                'message' => 'Response Flask API bukan JSON yang valid.',
                'http_status' => $httpCode,
                'raw_response' => $responseBody,
            ];
        }

        // Tambahkan data tambahan agar mudah disimpan ke database.
        $json['http_status']  = $httpCode;
        $json['raw_response'] = $responseBody;
        $json['latency_ms']   = $latency;

        return $json;
    }
}