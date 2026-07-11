<?php

namespace App\Models;

use CodeIgniter\Model;

class SystemSettingModel extends Model
{
    protected $table            = 'system_settings';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'description',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil value setting berdasarkan key.
     */
    public function getValue(string $key, ?string $default = null): ?string
    {
        $row = $this->where('setting_key', $key)->first();

        if (! $row) {
            return $default;
        }

        return $row['setting_value'] ?? $default;
    }

    /**
     * Update value setting berdasarkan key.
     */
    public function setValue(string $key, string $value): bool
    {
        $row = $this->where('setting_key', $key)->first();

        if (! $row) {
            return $this->insert([
                'setting_key'   => $key,
                'setting_value' => $value,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]) !== false;
        }

        return $this->update($row['id'], [
            'setting_value' => $value,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Ambil semua setting sebagai array key => value.
     */
    public function getAllKeyValue(): array
    {
        $rows = $this->findAll();
        $data = [];

        foreach ($rows as $row) {
            $data[$row['setting_key']] = $row['setting_value'];
        }

        return $data;
    }

    public function getBool(string $key, bool $default = false): bool
    {
        return filter_var($this->getValue($key, $default ? 'true' : 'false'), FILTER_VALIDATE_BOOLEAN);
    }

    public function getInt(string $key, int $default, int $min, int $max): int
    {
        $value = (int) $this->getValue($key, (string) $default);
        return max($min, min($max, $value));
    }

    public function getAllowedVideoTypes(): array
    {
        $configured = array_map(
            static fn (string $type): string => strtolower(trim($type)),
            explode(',', (string) $this->getValue('allowed_video_types', 'mp4,avi,mov,mkv'))
        );

        $allowed = array_values(array_unique(array_intersect($configured, ['mp4', 'avi', 'mov', 'mkv'])));
        return $allowed === [] ? ['mp4', 'avi', 'mov', 'mkv'] : $allowed;
    }
}
