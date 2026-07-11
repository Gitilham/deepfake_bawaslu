<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Filter ini memastikan user sudah login.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('is_logged_in')) {
            return redirect()
                ->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = (int) session()->get('user_id');
        $user = (new UserModel())->select('id, is_active')->where('deleted_at', null)->find($userId);
        if (! $user || (int) $user['is_active'] !== 1) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
