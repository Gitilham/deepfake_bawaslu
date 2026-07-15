<?php

namespace App\Libraries;

use Config\DeepfakeApi;
use CURLFile;

class DeepfakeApiClient
{
    public function __construct(private ?DeepfakeApi $config = null)
    {
        $this->config ??= config('DeepfakeApi');
    }

    public function getPredictUrl(): string { return $this->config->baseUrl . $this->config->predictEndpoint; }
    public function getHealthUrl(): string { return $this->config->baseUrl . $this->config->healthEndpoint; }

    public function predictVideo(string $temporaryPath, string $originalName, string $mimeType, string $requestId): array
    {
        if (! is_file($temporaryPath) || ! is_readable($temporaryPath)) return ['success'=>false,'message'=>'File upload sementara tidak dapat dibaca.'];
        return $this->request($this->getPredictUrl(), [
            'video' => new CURLFile($temporaryPath, $mimeType ?: 'application/octet-stream', basename($originalName)),
        ], $requestId);
    }

    public function testConnection(): array { return $this->request($this->getHealthUrl(), null, bin2hex(random_bytes(16))); }

    private function request(string $url, ?array $postFields, string $requestId): array
    {
        $started = hrtime(true);
        $ch = curl_init();
        $options = [CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>true, CURLOPT_CONNECTTIMEOUT=>$this->config->connectTimeout,
            CURLOPT_TIMEOUT=>$this->config->requestTimeout, CURLOPT_HTTPHEADER=>['Accept: application/json','X-Request-ID: '.$requestId]];
        if ($postFields !== null) $options += [CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>$postFields];
        curl_setopt_array($ch, $options);
        $body = curl_exec($ch); $error = curl_error($ch); $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        $elapsed = (hrtime(true) - $started) / 1e9;
        if ($error !== '') return ['success'=>false,'message'=>'Backend API tidak dapat dihubungi.','error_code'=>'BACKEND_CONNECTION_ERROR','http_status'=>$code,'api_seconds'=>$elapsed,'latency_ms'=>round($elapsed*1000,3)];
        $json = json_decode((string) $body, true);
        if (! is_array($json)) return ['success'=>false,'message'=>'Respons Backend API tidak valid.','error_code'=>'INVALID_BACKEND_JSON','http_status'=>$code,'api_seconds'=>$elapsed];
        if ($code < 200 || $code >= 300) return ['success'=>false,'message'=>$this->errorMessage($json),'error_code'=>'BACKEND_HTTP_ERROR','http_status'=>$code,'api_seconds'=>$elapsed,'latency_ms'=>round($elapsed*1000,3)];
        $json['success']=($json['success'] ?? true) === true; $json['http_status']=$code; $json['api_seconds']=$elapsed; $json['latency_ms']=round($elapsed*1000,3); $json['request_id']=$json['request_id'] ?? $requestId; $json['raw_response']=(string)$body;
        return $json;
    }

    private function errorMessage(array $response): string
    {
        if (is_string($response['message'] ?? null)) return $response['message'];
        if (is_string($response['detail'] ?? null)) return $response['detail'];
        if (is_array($response['detail'] ?? null) && is_string($response['detail'][0]['msg'] ?? null)) return $response['detail'][0]['msg'];
        return 'Backend menolak permintaan.';
    }
}
