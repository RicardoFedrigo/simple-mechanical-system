<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Services\AuthService;

class AuthController extends BaseController
{
    public function __construct(private AuthService $authService)
    {
    }

    public function loginForm(Request $request): string
    {
        $error = flash('error');
        return $this->view('auth/login', ['error' => $error]);
    }

    public function login(Request $request): void
    {
        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');

        if (!$this->authService->login($email, $password)) {
            flash('error', 'Invalid credentials.');
            $this->redirect('/login');
        }

        $this->redirect('/dashboard');
    }

    public function logout(Request $request): void
    {
        $this->authService->logout();
        $this->redirect('/login');
    }
}
