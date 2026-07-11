<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFrontendOptimizationSettings extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('system_settings')) {
            return;
        }
        $defaults = [
            'store_raw_video' => 'false',
            'raw_video_retention_days' => '7',
            'store_full_api_response' => 'false',
            'api_log_payload_max_bytes' => '16384',
            'store_frame_metadata' => 'false',
            'frame_metadata_retention_days' => '30',
            'health_cache_seconds' => '20',
            'max_video_size_mb' => '100',
            'allowed_video_types' => 'mp4,avi,mov,mkv',
        ];
        $builder = $this->db->table('system_settings');
        foreach ($defaults as $key => $value) {
            if (! $builder->where('setting_key', $key)->countAllResults()) {
                $builder->insert([
                    'setting_key' => $key,
                    'setting_value' => $value,
                    'description' => 'Konfigurasi frontend deteksi',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function down()
    {
        // Default aman dipertahankan saat rollback.
    }
}
