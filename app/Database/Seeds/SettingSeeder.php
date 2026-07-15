<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $builder = $this->db->table('system_settings');
        $now = date('Y-m-d H:i:s');

        $settings = [
            'flask_api_base_url' => [(string) env('ML_API_BASE_URL', 'http://deepfake-backend:5000'), 'Base URL Backend API'],
            'flask_api_predict_endpoint' => ['/predict-video', 'Endpoint prediksi video'],
            'max_video_size_mb' => ['100', 'Ukuran maksimal upload video dalam MB'],
            'allowed_video_types' => ['mp4,avi,mov,mkv', 'Ekstensi video yang diizinkan'],
            'store_raw_video' => ['false', 'Simpan file video asli setelah pemrosesan'],
            'raw_video_retention_days' => ['7', 'Retensi file video mentah dalam hari'],
            'store_full_api_response' => ['false', 'Simpan response API lengkap'],
            'api_log_payload_max_bytes' => ['16384', 'Batas payload log API dalam byte'],
            'store_frame_metadata' => ['false', 'Simpan metadata setiap frame'],
            'frame_metadata_retention_days' => ['30', 'Retensi metadata frame dalam hari'],
            'health_cache_seconds' => ['20', 'Durasi cache pemeriksaan health API'],
            'api_success_log_retention_days' => ['14', 'Retensi log API sukses dalam hari'],
            'api_error_log_retention_days' => ['90', 'Retensi log API error dalam hari'],
        ];

        foreach ($settings as $key => [$value, $description]) {
            if ($builder->where('setting_key', $key)->countAllResults() > 0) {
                continue;
            }

            $builder->insert([
                'setting_key' => $key,
                'setting_value' => $value,
                'description' => $description,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
