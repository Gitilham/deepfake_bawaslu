<?php

namespace App\Models;

use CodeIgniter\Model;

class FlaskApiLogModel extends Model
{
    protected $table            = 'flask_api_logs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'detection_id',
        'endpoint',
        'request_method',
        'http_status',
        'request_payload',
        'response_payload',
        'latency_ms',
        'error_message',
        'created_at',
    ];

    protected $useTimestamps = false;
}