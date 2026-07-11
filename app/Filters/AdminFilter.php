<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    /**
     * Filter ini membatasi halaman hanya untuk admin.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('role_name') !== 'admin') {
            return redirect()
                ->to('/login')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}