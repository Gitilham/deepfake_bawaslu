<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFlaskApiLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'detection_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'endpoint' => ['type' => 'VARCHAR', 'constraint' => 2048],
            'request_method' => ['type' => 'VARCHAR', 'constraint' => 10],
            'http_status' => ['type' => 'SMALLINT', 'unsigned' => true, 'null' => true],
            'request_payload' => ['type' => 'TEXT', 'null' => true],
            'response_payload' => ['type' => 'LONGTEXT', 'null' => true],
            'latency_ms' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'error_message' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['detection_id', 'created_at'], false, false, 'idx_api_logs_detection_created');
        $this->forge->addKey(['created_at', 'id'], false, false, 'idx_api_logs_created_id');
        $this->forge->addForeignKey('detection_id', 'video_detections', 'id', 'SET NULL', 'RESTRICT', 'fk_api_logs_detection');
        $this->forge->createTable('flask_api_logs', true, $this->tableAttributes());
    }

    public function down(): void
    {
        $this->forge->dropTable('flask_api_logs', true);
    }

    private function tableAttributes(): array
    {
        return ['ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_unicode_ci'];
    }
}
