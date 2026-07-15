<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'admin@deepfake.local';

    public function run(): void
    {
        $role = $this->db->table('roles')->where('role_name', 'admin')->get()->getRowArray();

        if ($role === null) {
            throw new RuntimeException('Role admin belum tersedia. Jalankan RoleSeeder terlebih dahulu.');
        }

        $users = $this->db->table('users');
        if ($users->where('email', self::ADMIN_EMAIL)->countAllResults() > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $users->insert([
            'role_id' => $role['id'],
            'full_name' => 'Administrator',
            'email' => self::ADMIN_EMAIL,
            'password' => password_hash('Admin123!', PASSWORD_DEFAULT),
            'phone' => null,
            'address' => null,
            'is_active' => 1,
            'last_login' => null,
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }
}
