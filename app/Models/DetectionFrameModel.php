<?php

namespace App\Models;

use CodeIgniter\Model;

class DetectionFrameModel extends Model
{
    protected $table            = 'detection_frames';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'detection_id',
        'frame_time',
        'label',
        'confidence',
        'real_score',
        'fake_score',
        'frame_path',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByDetection(int $detectionId): array
    {
        return $this->where('detection_id', $detectionId)
            ->orderBy('frame_time', 'ASC')
            ->findAll();
    }
}