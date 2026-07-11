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
        'request_id',
        'binary_prediction',
        'requires_manual_review',
        'review_status',
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
    public function getAllWithUser(array $filters = [], int $limit = 10): array
    {
        $builder = $this->select($this->listColumns(true))
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

        return $builder->orderBy('video_detections.id', 'DESC')->findAll(max(1, min(100, $limit)));
    }

    public function paginateWithUser(array $filters = [], int $perPage = 20, string $group = 'detections'): array
    {
        $builder = $this->select($this->listColumns(true))
            ->join('users', 'users.id = video_detections.user_id')
            ->where('video_detections.deleted_at', null);
        $this->applyFilters($builder, $filters);

        return $builder->orderBy('video_detections.id', 'DESC')->paginate(max(10, min(50, $perPage)), $group);
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
    public function getByUser(int $userId, int $limit = 10): array
    {
        return $this->select($this->listColumns(false))
            ->where('user_id', $userId)
            ->where('deleted_at', null)
            ->orderBy('id', 'DESC')
            ->findAll(max(1, min(100, $limit)));
    }

    public function paginateByUser(int $userId, int $perPage = 15): array
    {
        return $this->select($this->listColumns(false))
            ->where('user_id', $userId)
            ->where('deleted_at', null)
            ->orderBy('id', 'DESC')
            ->paginate(max(10, min(50, $perPage)), 'history');
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

    public function getReportSummary(array $filters = []): array
    {
        $builder = $this->builder();
        $builder->select("COUNT(*) AS total,
            SUM(CASE WHEN predicted_label = 'REAL' THEN 1 ELSE 0 END) AS real,
            SUM(CASE WHEN predicted_label = 'MENCURIGAKAN' THEN 1 ELSE 0 END) AS mencurigakan,
            SUM(CASE WHEN predicted_label = 'DEEPFAKE' THEN 1 ELSE 0 END) AS deepfake,
            SUM(CASE WHEN predicted_label = 'NO_FACE' THEN 1 ELSE 0 END) AS no_face,
            SUM(CASE WHEN predicted_label = 'UNKNOWN' THEN 1 ELSE 0 END) AS unknown,
            SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) AS failed,
            SUM(CASE WHEN requires_manual_review = 1 THEN 1 ELSE 0 END) AS requires_manual_review,
            SUM(CASE WHEN review_status = 'needs_review' THEN 1 ELSE 0 END) AS needs_review", false);
        $builder->where('deleted_at', null);
        $this->applyFilters($builder, $filters);
        $row = $builder->get()->getRowArray() ?? [];

        foreach (['total', 'real', 'mencurigakan', 'deepfake', 'no_face', 'unknown', 'failed', 'requires_manual_review', 'needs_review'] as $key) {
            $row[$key] = (int) ($row[$key] ?? 0);
        }

        return $row;
    }

    private function listColumns(bool $withUser): string
    {
        $columns = 'video_detections.id, video_detections.user_id, video_detections.original_filename, '
            . 'video_detections.status, video_detections.predicted_label, video_detections.confidence, '
            . 'video_detections.review_status, video_detections.requires_manual_review, video_detections.created_at';

        return $withUser ? $columns . ', users.full_name, users.email' : $columns;
    }

    private function applyFilters($builder, array $filters): void
    {
        if (! empty($filters['start_date'])) {
            $builder->where('video_detections.created_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (! empty($filters['end_date'])) {
            $builder->where('video_detections.created_at <=', $filters['end_date'] . ' 23:59:59');
        }
        if (! empty($filters['predicted_label'])) {
            $builder->where('video_detections.predicted_label', $filters['predicted_label']);
        }
        if (! empty($filters['status'])) {
            $builder->where('video_detections.status', $filters['status']);
        }
    }
}
