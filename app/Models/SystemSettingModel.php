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
}