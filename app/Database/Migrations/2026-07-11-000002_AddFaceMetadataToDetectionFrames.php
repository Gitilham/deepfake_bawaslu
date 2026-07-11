<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFaceMetadataToDetectionFrames extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('detection_frames')) {
            return;
        }
        $fields = [
            'source_frame_index' => ['type' => 'INT', 'null' => true],
            'face_detected' => ['type' => 'BOOLEAN', 'default' => false],
            'face_confidence' => ['type' => 'DECIMAL', 'constraint' => '10,6', 'null' => true],
            'crop_method' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'repeated_frame' => ['type' => 'BOOLEAN', 'default' => false],
            'bbox_json' => ['type' => 'TEXT', 'null' => true],
            'frame_status' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'note' => ['type' => 'TEXT', 'null' => true],
        ];
        foreach ($fields as $name => $definition) {
            if (! $this->db->fieldExists($name, 'detection_frames')) {
                $this->forge->addColumn('detection_frames', [$name => $definition]);
            }
        }
    }

    public function down()
    {
        // Sengaja non-destruktif untuk menjaga data frame lama.
    }
}
