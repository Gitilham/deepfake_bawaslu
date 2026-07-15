<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;
    protected RoleModel $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function login()
    {
        if (session()->get('is_logged_in')) {
            return $this->redirectByRole();
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $user = $this->userModel->getUserByEmail($email);

        if (! $user) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        if ((int) $user['is_active'] !== 1) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
        }

        if (! password_verify($password, $user['password'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id'      => $user['id'],
            'full_name'    => $user['full_name'],
            'email'        => $user['email'],
            'role_id'      => $user['role_id'],
            'role_name'    => $user['role_name'],
            'profile_photo' => $user['profile_photo'] ?? null,
            'is_logged_in' => true,
        ]);

        $this->userModel->update($user['id'], [
            'last_login' => date('Y-m-d H:i:s'),
        ]);

        return $this->redirectByRole();
    }

    public function register()
    {
        if (session()->get('is_logged_in')) {
            return $this->redirectByRole();
        }

        return view('auth/register');
    }

    public function attemptRegister()
    {
        $rules = [
            'full_name' => [
                'label' => 'Nama lengkap',
                'rules' => 'required|min_length[3]|max_length[150]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]',
            ],
            'phone' => [
                'label' => 'Nomor HP',
                'rules' => 'permit_empty|max_length[30]',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]',
            ],
            'password_confirm' => [
                'label' => 'Konfirmasi password',
                'rules' => 'required|matches[password]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $roleUser = $this->roleModel->where('role_name', 'user')->first();

        if (! $roleUser) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Role user masyarakat belum tersedia di database.');
        }

        $this->userModel->insert([
            'role_id'    => $roleUser['id'],
            'full_name'  => trim((string) $this->request->getPost('full_name')),
            'email'      => trim((string) $this->request->getPost('email')),
            'password'   => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'      => trim((string) $this->request->getPost('phone')),
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to('/login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout()
{
    session()->destroy();

    return redirect()->to(base_url('/'))
        ->with('success', 'Anda berhasil logout.');
}

    private function redirectByRole()
    {
        if (session()->get('role_name') === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        if (session()->get('role_name') === 'user') {
            return redirect()->to('/user/dashboard');
        }

        session()->destroy();

        return redirect()
            ->to('/login')
            ->with('error', 'Role akun tidak dikenali.');
    }
}
