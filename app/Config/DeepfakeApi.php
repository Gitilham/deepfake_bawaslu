<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class DeepfakeApi extends BaseConfig
{
    public string $baseUrl;
    public string $predictEndpoint;
    public string $healthEndpoint;
    public string $docsEndpoint;
    public int $maxVideoMb;
    public array $allowedFormats;
    public int $connectTimeout;
    public int $requestTimeout;

    public function __construct()
    {
        parent::__construct();
        $this->baseUrl = rtrim((string) env('DEEPFAKE_API_BASE_URL', 'http://deepfake-backend:5000'), '/');
        $this->predictEndpoint = '/' . ltrim((string) env('DEEPFAKE_API_PREDICT_ENDPOINT', '/predict-video'), '/');
        $this->healthEndpoint = '/' . ltrim((string) env('DEEPFAKE_API_HEALTH_ENDPOINT', '/health'), '/');
        $this->docsEndpoint = '/' . ltrim((string) env('DEEPFAKE_API_DOCS_ENDPOINT', '/docs'), '/');
        $this->maxVideoMb = max(1, min(1024, (int) env('DEEPFAKE_MAX_VIDEO_MB', 500)));
        $formats = array_map('trim', explode(',', strtolower((string) env('DEEPFAKE_ALLOWED_VIDEO_FORMATS', 'mp4,avi,mov,mkv,webm'))));
        $this->allowedFormats = array_values(array_unique(array_intersect($formats, ['mp4','avi','mov','mkv','webm'])));
        if ($this->allowedFormats === []) {
            $this->allowedFormats = ['mp4', 'avi', 'mov', 'mkv', 'webm'];
        }
        $this->connectTimeout = max(1, (int) env('DEEPFAKE_API_CONNECT_TIMEOUT', 15));
        $this->requestTimeout = max($this->connectTimeout, (int) env('DEEPFAKE_API_REQUEST_TIMEOUT', 900));
    }
}
