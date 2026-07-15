<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $builder = $this->db->table('roles');
        $now = date('Y-m-d H:i:s');

        $roles = [
            'admin' => 'Administrator aplikasi',
            'user' => 'Pengguna masyarakat',
        ];

        foreach ($roles as $name => $description) {
            if ($builder->where('role_name', $name)->countAllResults() > 0) {
                continue;
            }

            $builder->insert([
                'role_name' => $name,
                'description' => $description,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
