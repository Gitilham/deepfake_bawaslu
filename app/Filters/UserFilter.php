<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UserFilter implements FilterInterface
{
    /**
     * Filter ini membatasi halaman hanya untuk user masyarakat.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('role_name') !== 'user') {
            return redirect()
                ->to('/login')
                ->with('error', 'Anda tidak memiliki akses ke halaman user masyarakat.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}