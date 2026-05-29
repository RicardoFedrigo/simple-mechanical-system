<?php

namespace App\Middlewares;

use App\Core\Request;

class AdminMiddleware
{
    public function handle(Request $request): bool
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user || ($user['role'] ?? '') !== 'Admin') {
            header('Location: /dashboard');
            exit;
        }

        return true;
    }
}
