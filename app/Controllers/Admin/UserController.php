<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected UserModel $userModel;
    protected RoleModel $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Pengguna',
            'users' => $this->userModel->paginateUsersMasyarakat(20),
            'pager' => $this->userModel->pager,
        ];

        return view('admin/users/index', $data);
    }

    public function detail(int $id)
    {
        $user = $this->userModel->getUserWithRole($id);

        if (! $user) {
            return redirect()
                ->to('/admin/users')
                ->with('error', 'Data pengguna tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Pengguna',
            'user'  => $user,
            'roles' => $this->roleModel->orderBy('role_name', 'ASC')->findAll(),
        ];

        return view('admin/users/detail', $data);
    }

    public function edit(int $id)
    {
        return $this->detail($id);
    }

    public function update(int $id)
    {
        $user = $this->userModel->getUserWithRole($id);
        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'Data pengguna tidak ditemukan.');
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[150]',
            'email'     => 'required|valid_email|max_length[191]|is_unique[users.email,id,' . $id . ']',
            'role_id'   => 'required|integer|is_not_unique[roles.id]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $roleId = (int) $this->request->getPost('role_id');
        $role = $this->roleModel->find($roleId);
        if (! $role || ! in_array($role['role_name'], ['admin', 'user'], true)) {
            return redirect()->back()->withInput()->with('error', 'Role pengguna tidak valid.');
        }

        if ($id === (int) session()->get('user_id') && $role['role_name'] !== 'admin') {
            return redirect()->back()->withInput()->with('error', 'Anda tidak dapat menurunkan role akun admin yang sedang digunakan.');
        }

        if (($user['role_name'] ?? '') === 'admin' && $role['role_name'] !== 'admin') {
            $adminCount = $this->userModel
                ->join('roles', 'roles.id = users.role_id')
                ->where('roles.role_name', 'admin')
                ->where('users.deleted_at', null)
                ->countAllResults();
            if ($adminCount <= 1) {
                return redirect()->back()->withInput()->with('error', 'Role admin terakhir tidak dapat diubah.');
            }
        }

        $updated = $this->userModel->update($id, [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email'     => strtolower(trim((string) $this->request->getPost('email'))),
            'role_id'   => $roleId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if (! $updated) {
            return redirect()->back()->withInput()->with('error', 'Data pengguna gagal diperbarui.');
        }

        if ($id === (int) session()->get('user_id')) {
            session()->set([
                'full_name' => trim((string) $this->request->getPost('full_name')),
                'email'     => strtolower(trim((string) $this->request->getPost('email'))),
                'role'      => $role['role_name'],
                'role_name' => $role['role_name'],
            ]);
        }

        return redirect()->to('/admin/users')->with('success', 'Nama, email, dan role pengguna berhasil diperbarui.');
    }

    public function toggleStatus(int $id)
    {
        $user = $this->userModel->getUserWithRole($id);

        if (! $user) {
            return redirect()
                ->to('/admin/users')
                ->with('error', 'Data pengguna tidak ditemukan.');
        }

        $newStatus = ((int) $user['is_active'] === 1) ? 0 : 1;

        if ($id === (int) session()->get('user_id') && $newStatus === 0) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
        }

        if (($user['role_name'] ?? '') === 'admin' && $newStatus === 0) {
            $activeAdminCount = $this->userModel
                ->join('roles', 'roles.id = users.role_id')
                ->where('roles.role_name', 'admin')
                ->where('users.is_active', 1)
                ->where('users.deleted_at', null)
                ->countAllResults();
            if ($activeAdminCount <= 1) {
                return redirect()->to('/admin/users')->with('error', 'Admin aktif terakhir tidak dapat dinonaktifkan.');
            }
        }

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
