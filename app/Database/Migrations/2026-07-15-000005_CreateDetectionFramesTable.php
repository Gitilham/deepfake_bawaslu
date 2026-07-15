<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetectionFramesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'detection_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'frame_time' => ['type' => 'DECIMAL', 'constraint' => '12,3', 'null' => true],
            'label' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'default' => 'UNKNOWN'],
            'confidence' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'real_score' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'fake_score' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'frame_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'source_frame_index' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'face_detected' => ['type' => 'BOOLEAN', 'default' => false],
            'face_confidence' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'crop_method' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'repeated_frame' => ['type' => 'BOOLEAN', 'default' => false],
            'bbox_json' => ['type' => 'TEXT', 'null' => true],
            'frame_status' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'note' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['detection_id', 'frame_time'], false, false, 'idx_frames_detection_time');
        $this->forge->addKey('created_at', false, false, 'idx_frames_created_at');
        $this->forge->addForeignKey('detection_id', 'video_detections', 'id', 'RESTRICT', 'RESTRICT', 'fk_frames_detection');
        $this->forge->createTable('detection_frames', true, $this->tableAttributes());
    }

    public function down(): void
    {
        $this->forge->dropTable('detection_frames', true);
    }

    private function tableAttributes(): array
    {
        return ['ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_unicode_ci'];
    }
}
