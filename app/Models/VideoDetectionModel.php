<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
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
        'request_id',
        'binary_prediction',
        'requires_manual_review',
        'review_status',
        'reviewed_by',
        'reviewed_at',
        'model_version',
        'threshold',
        'margin',
        'confidence_note',
        'decision_rule',
        'decision_explanation',
        'frames_used',
        'face_detected_count',
        'min_face_frames',
        'api_latency_ms',
        'file_deleted_at',
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
     * Data deteksi lengkap dengan user untuk daftar admin dan laporan.
     */
    public function paginateWithUser(array $filters = [], int $perPage = 20, string $group = 'detections'): array
    {
        $this->select('video_detections.*, users.full_name, users.email')
            ->join('users', 'users.id = video_detections.user_id')
            ->where('video_detections.deleted_at', null);

        $this->applyModelFilters($filters);

        return $this->orderBy('video_detections.id', 'DESC')
            ->paginate(max(1, min(100, $perPage)), $group);
    }

    /**
     * Ringkasan laporan dengan filter yang sama seperti daftar laporan.
     *
     * @return array{total:int, real:int, deepfake:int, failed:int}
     */
    public function getReportSummary(array $filters = []): array
    {
        $builder = $this->db->table($this->table)
            ->select('COUNT(*) AS total', false)
            ->select("COALESCE(SUM(CASE WHEN predicted_label = 'REAL' THEN 1 ELSE 0 END), 0) AS total_real", false)
            ->select("COALESCE(SUM(CASE WHEN predicted_label = 'DEEPFAKE' THEN 1 ELSE 0 END), 0) AS total_deepfake", false)
            ->select("COALESCE(SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END), 0) AS total_failed", false)
            ->where('deleted_at', null);

        $this->applyBuilderFilters($builder, $filters);
        $row = $builder->get()->getRowArray() ?? [];

        return [
            'total' => (int) ($row['total'] ?? 0),
            'real' => (int) ($row['total_real'] ?? 0),
            'deepfake' => (int) ($row['total_deepfake'] ?? 0),
            'failed' => (int) ($row['total_failed'] ?? 0),
        ];
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

    private function applyModelFilters(array $filters): void
    {
        if (! empty($filters['start_date'])) {
            $this->where('video_detections.created_at >=', $filters['start_date'] . ' 00:00:00');
        }

        if (! empty($filters['end_date'])) {
            $this->where('video_detections.created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        if (! empty($filters['predicted_label'])) {
            $this->where('video_detections.predicted_label', $filters['predicted_label']);
        }

        if (! empty($filters['status'])) {
            $this->where('video_detections.status', $filters['status']);
        }
    }

    private function applyBuilderFilters(BaseBuilder $builder, array $filters): void
    {
        if (! empty($filters['start_date'])) {
            $builder->where('created_at >=', $filters['start_date'] . ' 00:00:00');
        }

        if (! empty($filters['end_date'])) {
            $builder->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        if (! empty($filters['predicted_label'])) {
            $builder->where('predicted_label', $filters['predicted_label']);
        }

        if (! empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
    }
}
