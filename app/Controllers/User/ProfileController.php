<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');

        if (! $userId) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = $this->userModel->find($userId);

        if (! $user) {
            return redirect()->to(base_url('logout'))
                ->with('error', 'Data user tidak ditemukan.');
        }

        $data = [
            'title' => 'Profil Saya',
            'user'  => $user,
        ];

        return view('user/profile/index', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id');

        if (! $userId) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $rules = [
            'full_name' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required|min_length[3]',
            ],
            'phone' => [
                'label' => 'Nomor HP',
                'rules' => 'permit_empty|max_length[30]',
            ],
            'address' => [
                'label' => 'Alamat',
                'rules' => 'permit_empty',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $data = [
            'full_name'  => trim((string) $this->request->getPost('full_name')),
            'phone'      => trim((string) $this->request->getPost('phone')),
            'address'    => trim((string) $this->request->getPost('address')),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->userModel->update($userId, $data);

        session()->set([
            'full_name' => $data['full_name'],
        ]);

        return redirect()->to(base_url('user/profile'))
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword()
    {
        $userId = session()->get('user_id');

        if (! $userId) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = $this->userModel->find($userId);

        if (! $user) {
            return redirect()->to(base_url('logout'))
                ->with('error', 'Data user tidak ditemukan.');
        }

        $rules = [
            'old_password' => [
                'label' => 'Password Lama',
                'rules' => 'required',
            ],
            'new_password' => [
                'label' => 'Password Baru',
                'rules' => 'required|min_length[8]',
            ],
            'new_password_confirm' => [
                'label' => 'Konfirmasi Password Baru',
                'rules' => 'required|matches[new_password]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $oldPassword = (string) $this->request->getPost('old_password');
        $newPassword = (string) $this->request->getPost('new_password');

        /*
         * Menyesuaikan field password.
         * Kalau tabel users kamu memakai password_hash, pakai password_hash.
         * Kalau memakai password, pakai password.
         */
        $passwordField = array_key_exists('password_hash', $user) ? 'password_hash' : 'password';

        if (! isset($user[$passwordField]) || ! password_verify($oldPassword, $user[$passwordField])) {
            return redirect()->back()
                ->with('error', 'Password lama tidak sesuai.');
        }

        $this->userModel->update($userId, [
            $passwordField => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('user/profile'))
            ->with('success', 'Password berhasil diubah.');
    }
}