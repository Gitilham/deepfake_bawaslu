<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPredictionMetadataToVideoDetections extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('video_detections')) {
            return;
        }

        $fields = [
            'request_id' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'binary_prediction' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'requires_manual_review' => ['type' => 'BOOLEAN', 'default' => false],
            'review_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'unreviewed'],
            'reviewed_by' => ['type' => 'BIGINT', 'null' => true],
            'reviewed_at' => ['type' => 'DATETIME', 'null' => true],
            'model_version' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'threshold' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'margin' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'confidence_note' => ['type' => 'TEXT', 'null' => true],
            'decision_rule' => ['type' => 'TEXT', 'null' => true],
            'decision_explanation' => ['type' => 'TEXT', 'null' => true],
            'frames_used' => ['type' => 'INT', 'null' => true],
            'face_detected_count' => ['type' => 'INT', 'null' => true],
            'min_face_frames' => ['type' => 'INT', 'null' => true],
            'api_latency_ms' => ['type' => 'INT', 'null' => true],
            'file_deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        foreach ($fields as $name => $definition) {
            if (! $this->db->fieldExists($name, 'video_detections')) {
                $this->forge->addColumn('video_detections', [$name => $definition]);
            }
        }

        // Video tidak disimpan secara default dan label backend kini memiliki lima nilai valid.
        foreach (['stored_filename', 'file_path'] as $nullableColumn) {
            if ($this->db->fieldExists($nullableColumn, 'video_detections')) {
                $this->forge->modifyColumn('video_detections', [
                    $nullableColumn => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                ]);
            }
        }
        if ($this->db->fieldExists('predicted_label', 'video_detections')) {
            $this->forge->modifyColumn('video_detections', [
                'predicted_label' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            ]);
        }

        foreach (['user_id', 'status', 'predicted_label', 'review_status', 'created_at', 'request_id', 'requires_manual_review'] as $column) {
            $this->addIndexIfMissing('video_detections', $column);
        }
    }

    public function down()
    {
        // Migration additive: data dan kolom dipertahankan agar rollback tidak destruktif.
    }

    private function addIndexIfMissing(string $table, string $column): void
    {
        if (! $this->db->fieldExists($column, $table)) {
            return;
        }
        foreach ($this->db->getIndexData($table) as $index) {
            if (in_array($column, $index->fields ?? [], true)) {
                return;
            }
        }
        $name = 'idx_' . $table . '_' . $column;
        $this->db->query('CREATE INDEX ' . $this->db->escapeIdentifiers($name)
            . ' ON ' . $this->db->escapeIdentifiers($table)
            . ' (' . $this->db->escapeIdentifiers($column) . ')');
    }
}
