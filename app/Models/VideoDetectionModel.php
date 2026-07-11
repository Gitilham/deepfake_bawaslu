<?php

namespace App\Models;

use CodeIgniter\Model;

class VideoDetectionModel extends Model
{
    protected $table            = 'video_detections';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $allowedFields = [
        'user_id',
        'original_filename',
        'stored_filename',
        'file_path',
        'file_mime',
        'file_size',
        'status',
        'predicted_label',
        'confidence',
        'real_score',
        'fake_score',
        'duration_seconds',
        'api_response_json',
        'error_message',
        'reviewed_by',
        'reviewed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Data deteksi lengkap dengan nama user.
     */
    public function getAllWithUser(array $filters = []): array
    {
        $builder = $this->select('video_detections.*, users.full_name, users.email')
            ->join('users', 'users.id = video_detections.user_id')
            ->where('video_detections.deleted_at', null);

        if (! empty($filters['start_date'])) {
            $builder->where('DATE(video_detections.created_at) >=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $builder->where('DATE(video_detections.created_at) <=', $filters['end_date']);
        }

        if (! empty($filters['predicted_label'])) {
            $builder->where('video_detections.predicted_label', $filters['predicted_label']);
        }

        if (! empty($filters['status'])) {
            $builder->where('video_detections.status', $filters['status']);
        }

        return $builder->orderBy('video_detections.id', 'DESC')->findAll();
    }

    /**
     * Detail deteksi untuk admin.
     */
    public function getDetailWithUser(int $id): ?array
    {
        return $this->select('video_detections.*, users.full_name, users.email')
            ->join('users', 'users.id = video_detections.user_id')
            ->where('video_detections.id', $id)
            ->where('video_detections.deleted_at', null)
            ->first();
    }

    /**
     * Detail deteksi milik user tertentu.
     */
    public function getUserDetectionDetail(int $id, int $userId): ?array
    {
        return $this->where('id', $id)
            ->where('user_id', $userId)
            ->where('deleted_at', null)
            ->first();
    }

    /**
     * Riwayat deteksi milik user.
     */
    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->where('deleted_at', null)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Hitung data berdasarkan kondisi.
     */
    public function countByCondition(array $condition): int
    {
        return $this->where($condition)
            ->where('deleted_at', null)
            ->countAllResults();
    }
}