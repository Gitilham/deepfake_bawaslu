<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $allowedFields = [
        'role_id',
        'full_name',
        'email',
        'password',
        'phone',
        'address',
        'profile_photo',
        'is_active',
        'last_login',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil user berdasarkan email beserta role.
     */
    public function getUserByEmail(string $email): ?array
    {
        return $this->select('users.*, roles.role_name')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.email', $email)
            ->where('users.deleted_at', null)
            ->first();
    }

    /**
     * Ambil user berdasarkan id beserta role.
     */
    public function getUserWithRole(int $id): ?array
    {
        return $this->select('users.*, roles.role_name')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.id', $id)
            ->where('users.deleted_at', null)
            ->first();
    }

    /**
     * Ambil semua user masyarakat.
     */
    public function getAllUsersMasyarakat(): array
    {
        return $this->select('users.*, roles.role_name')
            ->join('roles', 'roles.id = users.role_id')
            ->where('roles.role_name', 'user')
            ->orderBy('users.id', 'DESC')
            ->findAll();
    }

    public function paginateUsersMasyarakat(int $perPage = 20): array
    {
        return $this->select('users.id, users.full_name, users.email, users.phone, users.is_active, users.last_login, users.created_at, roles.role_name')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.deleted_at', null)
            ->orderBy('users.id', 'DESC')
            ->paginate(max(10, min(50, $perPage)), 'users');
    }
}
