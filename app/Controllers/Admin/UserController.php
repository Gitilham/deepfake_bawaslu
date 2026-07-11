<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data User Masyarakat',
            'users' => $this->userModel->getAllUsersMasyarakat(),
        ];

        return view('admin/users/index', $data);
    }

    public function detail(int $id)
    {
        $user = $this->userModel->getUserWithRole($id);

        if (! $user || $user['role_name'] !== 'user') {
            return redirect()
                ->to('/admin/users')
                ->with('error', 'Data user tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail User',
            'user'  => $user,
        ];

        return view('admin/users/detail', $data);
    }

    public function toggleStatus(int $id)
    {
        $user = $this->userModel->getUserWithRole($id);

        if (! $user || $user['role_name'] !== 'user') {
            return redirect()
                ->to('/admin/users')
                ->with('error', 'Data user tidak ditemukan.');
        }

        $newStatus = ((int) $user['is_active'] === 1) ? 0 : 1;

        $this->userModel->update($id, [
            'is_active'  => $newStatus,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $message = $newStatus === 1
            ? 'User berhasil diaktifkan.'
            : 'User berhasil dinonaktifkan.';

        return redirect()
            ->to('/admin/users')
            ->with('success', $message);
    }
}