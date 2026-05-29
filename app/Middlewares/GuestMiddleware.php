<?php

namespace App\Middlewares;

use App\Core\Request;

class GuestMiddleware
{
    public function handle(Request $request): bool
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /dashboard');
            exit;
        }

        return true;
    }
}
