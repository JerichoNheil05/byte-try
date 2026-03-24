<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter - Protects routes requiring authentication
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            // Store intended URL for redirect after login
            $session->set('redirect_url', current_url());
            
            // Redirect to login page
            return redirect()->to('/auth/login')->with('error', 'Please login to continue');
        }

        // Check role-based access if specified
        if ($arguments) {
            $userRole = $session->get('role');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/dashboard')->with('error', 'Access denied');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
