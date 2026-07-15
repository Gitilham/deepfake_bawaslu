<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEducationContentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 191],
            'content' => ['type' => 'LONGTEXT'],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_education_contents_slug');
        $this->forge->addKey(['is_active', 'deleted_at', 'id'], false, false, 'idx_education_active_deleted_id');
        $this->forge->addKey('created_by', false, false, 'idx_education_created_by');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'RESTRICT', 'fk_education_creator');
        $this->forge->createTable('education_contents', true, $this->tableAttributes());
    }

    public function down(): void
    {
        $this->forge->dropTable('education_contents', true);
    }

    private function tableAttributes(): array
    {
        return ['ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_unicode_ci'];
    }
}
