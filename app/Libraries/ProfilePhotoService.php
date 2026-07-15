<?php

namespace App\Libraries;

use App\Models\UserModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

class ProfilePhotoService
{
    public function store(UploadedFile $photo, int $userId, UserModel $userModel): string
    {
        if (! $photo->isValid() || $photo->hasMoved()) {
            throw new RuntimeException('File foto profil tidak valid.');
        }

        $user = $userModel->find($userId);
        if (! $user) {
            throw new RuntimeException('Data pengguna tidak ditemukan.');
        }

        $directory = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'profiles';
        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException('Direktori foto profil tidak dapat dibuat.');
        }

        if (! is_writable($directory)) {
            throw new RuntimeException('Direktori foto profil tidak memiliki izin tulis.');
        }

        $filename = 'profile_' . $userId . '_' . bin2hex(random_bytes(8)) . '.' . $photo->guessExtension();
        $photo->move($directory, $filename);
        $relativePath = 'uploads/profiles/' . $filename;

        $oldRelativePath = (string) ($user['profile_photo'] ?? '');
        if ($oldRelativePath !== '') {
            $oldPath = realpath(FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $oldRelativePath));
            $profileRoot = realpath($directory);
            if ($oldPath !== false && $profileRoot !== false
                && str_starts_with($oldPath, $profileRoot . DIRECTORY_SEPARATOR) && is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        return $relativePath;
    }
}
