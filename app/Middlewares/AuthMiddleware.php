<?php

namespace App\Middlewares;

use App\Core\Request;

class AuthMiddleware
{
    public function handle(Request $request): bool
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        return true;
    }
}
