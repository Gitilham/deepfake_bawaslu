<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Libraries\ProfilePhotoService;
use Throwable;

class ProfileController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');

        $data = [
            'title' => 'Profil Admin',
            'user'  => $this->userModel->find($userId),
        ];

        return view('admin/profile/index', $data);
    }

    public function update()
    {
        $rules = [
            'full_name' => [
                'label' => 'Nama lengkap',
                'rules' => 'required|min_length[3]|max_length[150]',
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
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userId = (int) session()->get('user_id');

        $fullName = trim((string) $this->request->getPost('full_name'));

        $photo = $this->request->getFile('profile_photo');
        $hasPhoto = $photo !== null && $photo->getError() !== UPLOAD_ERR_NO_FILE;
        if ($hasPhoto && ! $this->validate([
            'profile_photo' => [
                'label' => 'Foto profil',
                'rules' => 'uploaded[profile_photo]|is_image[profile_photo]|mime_in[profile_photo,image/jpeg,image/png,image/webp]|max_size[profile_photo,2048]|max_dims[profile_photo,2048,2048]',
            ],
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name' => $fullName,
            'phone' => trim((string) $this->request->getPost('phone')),
            'address' => trim((string) $this->request->getPost('address')),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            if ($hasPhoto && $photo !== null) {
                $data['profile_photo'] = (new ProfilePhotoService())->store($photo, $userId, $this->userModel);
            }
        } catch (Throwable $error) {
            return redirect()->back()->withInput()->with('error', $error->getMessage());
        }

        $this->userModel->update($userId, $data);

        session()->set([
            'full_name' => $fullName,
            'profile_photo' => $data['profile_photo'] ?? session()->get('profile_photo'),
        ]);

        return redirect()
            ->to('/admin/profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword()
    {
        $rules = [
            'old_password' => [
                'label' => 'Password lama',
                'rules' => 'required',
            ],
            'new_password' => [
                'label' => 'Password baru',
                'rules' => 'required|min_length[8]',
            ],
            'new_password_confirm' => [
                'label' => 'Konfirmasi password baru',
                'rules' => 'required|matches[new_password]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->with('errors', $this->validator->getErrors());
        }

        $userId = (int) session()->get('user_id');
        $user   = $this->userModel->find($userId);

        if (! $user) {
            return redirect()
                ->to('/login')
                ->with('error', 'Session tidak valid. Silakan login ulang.');
        }

        if (! password_verify((string) $this->request->getPost('old_password'), $user['password'])) {
            return redirect()
                ->back()
                ->with('error', 'Password lama tidak sesuai.');
        }

        $this->userModel->update($userId, [
            'password'   => password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to('/admin/profile')
            ->with('success', 'Password berhasil diubah.');
    }
}
