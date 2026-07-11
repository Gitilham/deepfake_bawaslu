<?php

namespace App\Libraries;

use App\Models\FlaskApiLogModel;
use App\Models\SystemSettingModel;
use CURLFile;
use RuntimeException;

class FlaskApiService
{
    private const RESULT_STATUSES = ['REAL', 'MENCURIGAKAN', 'DEEPFAKE', 'NO_FACE', 'UNKNOWN'];
    private const BINARY_RESULTS = ['REAL', 'DEEPFAKE'];

    protected SystemSettingModel $settingModel;
    protected FlaskApiLogModel $logModel;

    public function __construct(?SystemSettingModel $settingModel = null, ?FlaskApiLogModel $logModel = null)
    {
        $this->settingModel = $settingModel ?? new SystemSettingModel();
        $this->logModel = $logModel ?? new FlaskApiLogModel();
    }

    public function getBaseUrl(): string
    {
        $url = rtrim((string) $this->settingModel->getValue('flask_api_base_url', 'http://127.0.0.1:5000'), '/');
        $parts = parse_url($url);

        if (! is_array($parts)
            || ! in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true)
            || empty($parts['host'])
            || isset($parts['user'])
            || isset($parts['pass'])
            || preg_match('/[\x00-\x1F\x7F]/', $url)
        ) {
            throw new RuntimeException('Konfigurasi URL backend AI tidak valid.');
        }

        $allowedHosts = array_filter(array_map('trim', explode(',', (string) env(
            'ML_API_ALLOWED_HOSTS',
            '127.0.0.1,localhost,deepfake-api'
        ))));

        if ($allowedHosts !== [] && ! in_array(strtolower((string) $parts['host']), array_map('strtolower', $allowedHosts), true)) {
            throw new RuntimeException('Host backend AI tidak termasuk dalam allowlist.');
        }

        return $url;
    }

    public function getPredictEndpoint(): string
    {
        $endpoint = trim((string) $this->settingModel->getValue('flask_api_predict_endpoint', '/predict-video'));

        if ($endpoint === '' || ! str_starts_with($endpoint, '/') || preg_match('/[\x00-\x1F\x7F]/', $endpoint)) {
            throw new RuntimeException('Konfigurasi endpoint prediksi tidak valid.');
        }

        return $endpoint;
    }

    public function getPredictUrl(): string
    {
        return $this->getBaseUrl() . $this->getPredictEndpoint();
    }

    public function checkHealth(bool $useCache = true): array
    {
        $cacheKey = 'ml_api_health_' . sha1($this->getBaseUrl());
        $cache = service('cache');

        if ($useCache && ($cached = $cache->get($cacheKey)) && is_array($cached)) {
            return $cached;
        }

        $result = $this->requestHealth();
        $seconds = max(15, min(30, (int) $this->settingModel->getValue('health_cache_seconds', '20')));
        $cache->save($cacheKey, $result, $seconds);

        return $result;
    }

    public function testConnection(): array
    {
        return $this->checkHealth(false);
    }

    private function requestHealth(): array
    {
        $url = $this->getBaseUrl() . '/health';
        $start = microtime(true);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $latency = (int) round((microtime(true) - $start) * 1000);
        curl_close($ch);

        $json = is_string($body) ? json_decode($body, true) : null;
        $healthy = $error === ''
            && $httpCode >= 200 && $httpCode < 300
            && is_array($json)
            && ($json['success'] ?? false) === true;
        $status = $healthy ? 'healthy' : (($httpCode === 503 || (is_array($json) && ($json['success'] ?? true) === false))
            ? 'model_error'
            : 'connection_error');
        $message = $healthy
            ? 'Model Siap'
            : ($status === 'model_error' ? 'Backend aktif tetapi model belum siap.' : 'Model Tidak Tersedia');

        $result = [
            'success' => $healthy,
            'status' => $status,
            'message' => $message,
            'http_status' => $httpCode,
            'latency_ms' => $latency,
        ];
        $this->writeLog(null, $url, 'GET', $result, $healthy ? null : $this->extractErrorMessage($json ?? $body));

        return $result;
    }

    /** @param array<string,mixed>|int|null $metadata */
    public function predictVideo(string $videoPath, array|int|null $metadata = []): array
    {
        $metadata = is_int($metadata) ? ['detection_id' => $metadata] : ($metadata ?? []);
        $detectionId = isset($metadata['detection_id']) ? (int) $metadata['detection_id'] : null;

        if (! is_file($videoPath) || ! is_readable($videoPath) || filesize($videoPath) <= 0) {
            return $this->normalizePredictionResponse([
                'success' => false,
                'message' => 'File video sementara tidak valid.',
            ]);
        }

        $url = $this->getPredictUrl();
        $mimeType = mime_content_type($videoPath) ?: 'application/octet-stream';
        $displayName = basename((string) ($metadata['original_filename'] ?? 'video-upload'));
        $start = microtime(true);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['video' => new CURLFile($videoPath, $mimeType, $displayName)],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $latency = (int) round((microtime(true) - $start) * 1000);
        curl_close($ch);

        $json = is_string($body) ? json_decode($body, true) : null;
        if ($error !== '') {
            $json = ['success' => false, 'message' => 'Backend AI tidak dapat dihubungi.'];
        } elseif (! is_array($json)) {
            $json = ['success' => false, 'message' => 'Response backend AI tidak valid.'];
        } elseif ($httpCode < 200 || $httpCode >= 300) {
            $json['message'] = $this->extractErrorMessage($json);
            $json['success'] = false;
        }

        $json['http_status'] = $httpCode;
        $json['latency_ms'] = $latency;
        $normalized = $this->normalizePredictionResponse($json);
        $logError = $normalized['success'] ? null : $this->extractErrorMessage($json);
        $this->writeLog($detectionId, $url, 'POST', $normalized, $logError, is_string($body) ? $body : null);

        return $normalized;
    }

    public function normalizePredictionResponse(array $response): array
    {
        $resultStatus = strtoupper((string) ($response['result_status'] ?? $response['status'] ?? $response['label'] ?? $response['prediction'] ?? 'UNKNOWN'));
        if (! in_array($resultStatus, self::RESULT_STATUSES, true)) {
            $resultStatus = 'UNKNOWN';
        }

        $binary = strtoupper((string) ($response['binary_prediction'] ?? $response['prediction'] ?? ''));
        $binary = in_array($binary, self::BINARY_RESULTS, true) ? $binary : null;
        $success = filter_var($response['success'] ?? false, FILTER_VALIDATE_BOOLEAN);

        return [
            'success' => $success,
            'request_id' => $this->nullableString($response['request_id'] ?? null),
            'result_status' => $resultStatus,
            'binary_prediction' => $binary,
            'requires_manual_review' => filter_var(
                $response['requires_manual_review'] ?? ($resultStatus === 'MENCURIGAKAN'),
                FILTER_VALIDATE_BOOLEAN
            ),
            'confidence' => $this->nullableFloat($response['confidence'] ?? null),
            'real_score' => $this->nullableFloat($response['real_score'] ?? null),
            'fake_score' => $this->nullableFloat($response['fake_score'] ?? null),
            'base_score_fake' => $this->nullableFloat($response['base_score_fake'] ?? null),
            'local_score_fake' => $this->nullableFloat($response['local_score_fake'] ?? null),
            'threshold' => $this->nullableFloat($response['threshold'] ?? null),
            'margin' => $this->nullableFloat($response['margin'] ?? null),
            'confidence_note' => $this->nullableString($response['confidence_note'] ?? null),
            'decision_rule' => $this->nullableString($response['decision_rule'] ?? null),
            'decision_explanation' => $this->nullableString($response['decision_explanation'] ?? null),
            'model_version' => $this->nullableString($response['model_version'] ?? null),
            'duration_seconds' => $this->nullableFloat($response['duration_seconds'] ?? null),
            'frames_used' => $this->nullableInt($response['frames_used'] ?? null),
            'face_detected_count' => $this->nullableInt($response['face_detected_count'] ?? null),
            'min_face_frames' => $this->nullableInt($response['min_face_frames'] ?? null),
            'message' => $success ? (string) ($response['message'] ?? 'Prediksi berhasil.') : $this->extractErrorMessage($response),
            'frames' => is_array($response['frames'] ?? null) ? $response['frames'] : [],
            'feature_debug' => is_array($response['feature_debug'] ?? null) ? $response['feature_debug'] : [],
            'http_status' => (int) ($response['http_status'] ?? 0),
            'latency_ms' => (int) ($response['latency_ms'] ?? 0),
        ];
    }

    public function extractErrorMessage(array|string|null $response): string
    {
        if (is_string($response)) {
            return 'Backend AI mengembalikan response yang tidak dapat diproses.';
        }
        if (! is_array($response)) {
            return 'Terjadi kesalahan saat memproses prediksi.';
        }
        if (is_string($response['message'] ?? null) && trim($response['message']) !== '') {
            return $this->safeMessage($response['message']);
        }
        if (is_string($response['detail'] ?? null) && trim($response['detail']) !== '') {
            return $this->safeMessage($response['detail']);
        }
        if (is_array($response['detail'] ?? null)) {
            $messages = [];
            foreach ($response['detail'] as $item) {
                if (is_array($item) && is_string($item['msg'] ?? null)) {
                    $messages[] = $item['msg'];
                }
            }
            if ($messages !== []) {
                return $this->safeMessage(implode('; ', $messages));
            }
        }
        if (is_string($response['error'] ?? null) && trim($response['error']) !== '') {
            return $this->safeMessage($response['error']);
        }
        return 'Terjadi kesalahan saat memproses prediksi.';
    }

    public function buildCompactLog(array $response): array
    {
        return [
            'request_id' => $response['request_id'] ?? null,
            'http_status' => (int) ($response['http_status'] ?? 0),
            'latency_ms' => (int) ($response['latency_ms'] ?? 0),
            'result_status' => $response['result_status'] ?? 'UNKNOWN',
            'message' => $this->safeMessage((string) ($response['message'] ?? '')),
        ];
    }

    private function writeLog(?int $detectionId, string $url, string $method, array $result, ?string $error, ?string $rawBody = null): void
    {
        $payload = $this->buildCompactLog($result);
        $storeFull = filter_var($this->settingModel->getValue('store_full_api_response', (string) env('STORE_FULL_API_RESPONSE', 'false')), FILTER_VALIDATE_BOOLEAN);
        if ($error !== null && $storeFull && $rawBody !== null) {
            $max = max(1024, min(32768, (int) $this->settingModel->getValue('api_log_payload_max_bytes', (string) env('API_LOG_PAYLOAD_MAX_BYTES', '16384'))));
            $payload['error_response'] = mb_substr($rawBody, 0, $max);
        }

        try {
            $this->logModel->insert([
                'detection_id' => $detectionId,
                'endpoint' => $url,
                'request_method' => $method,
                'http_status' => (int) ($result['http_status'] ?? 0),
                'request_payload' => null,
                'response_payload' => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'latency_ms' => (int) ($result['latency_ms'] ?? 0),
                'error_message' => $error,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $exception) {
            log_message('warning', 'Gagal menyimpan log backend AI: {message}', ['message' => $exception->getMessage()]);
        }
    }

    private function nullableString(mixed $value): ?string
    {
        return is_scalar($value) && trim((string) $value) !== '' ? trim((string) $value) : null;
    }

    private function nullableFloat(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private function nullableInt(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function safeMessage(string $message): string
    {
        $message = preg_replace('/(?:[A-Za-z]:[\\\\\/]|\/)[^\s]+/', '[path]', strip_tags($message)) ?? '';
        return mb_substr(trim($message), 0, 500) ?: 'Terjadi kesalahan saat memproses prediksi.';
    }
}
