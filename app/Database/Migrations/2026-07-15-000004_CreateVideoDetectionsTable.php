<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVideoDetectionsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'original_filename' => ['type' => 'VARCHAR', 'constraint' => 255],
            'stored_filename' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_mime' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'file_size' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
            'predicted_label' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'default' => 'UNKNOWN'],
            'confidence' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'real_score' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'fake_score' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'duration_seconds' => ['type' => 'DECIMAL', 'constraint' => '12,3', 'null' => true],
            'api_response_json' => ['type' => 'LONGTEXT', 'null' => true],
            'error_message' => ['type' => 'TEXT', 'null' => true],
            'request_id' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'binary_prediction' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'requires_manual_review' => ['type' => 'BOOLEAN', 'default' => false],
            'review_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'unreviewed'],
            'reviewed_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'reviewed_at' => ['type' => 'DATETIME', 'null' => true],
            'model_version' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'threshold' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'margin' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'confidence_note' => ['type' => 'TEXT', 'null' => true],
            'decision_rule' => ['type' => 'TEXT', 'null' => true],
            'decision_explanation' => ['type' => 'TEXT', 'null' => true],
            'frames_used' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'face_detected_count' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'min_face_frames' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'api_latency_ms' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'file_deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'deleted_at', 'id'], false, false, 'idx_detections_user_deleted_id');
        $this->forge->addKey(['deleted_at', 'created_at'], false, false, 'idx_detections_deleted_created');
        $this->forge->addKey(['predicted_label', 'deleted_at', 'created_at'], false, false, 'idx_detections_label_deleted_created');
        $this->forge->addKey(['status', 'deleted_at', 'created_at'], false, false, 'idx_detections_status_deleted_created');
        $this->forge->addKey('review_status', false, false, 'idx_detections_review_status');
        $this->forge->addKey('reviewed_by', false, false, 'idx_detections_reviewed_by');
        $this->forge->addKey('request_id', false, false, 'idx_detections_request_id');
        $this->forge->addKey('requires_manual_review', false, false, 'idx_detections_manual_review');
        $this->forge->addKey(['file_deleted_at', 'created_at'], false, false, 'idx_detections_file_cleanup');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_detections_user');
        $this->forge->addForeignKey('reviewed_by', 'users', 'id', 'SET NULL', 'RESTRICT', 'fk_detections_reviewer');
        $this->forge->createTable('video_detections', true, $this->tableAttributes());
    }

    public function down(): void
    {
        $this->forge->dropTable('video_detections', true);
    }

    private function tableAttributes(): array
    {
        return ['ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_unicode_ci'];
    }
}
