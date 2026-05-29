<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;

class HomeController extends BaseController
{
    public function dashboard(Request $request): string
    {
        return $this->view('dashboard/index', [
            'title' => 'Workshop Dashboard',
            'user' => $_SESSION['user'] ?? [],
            'stats' => [
                'customers' => 18,
                'vehicles' => 12,
                'finished' => 7,
                'revenue' => '$24,500',
            ],
        ]);
    }

    public function customers(Request $request): string
    {
        return $this->view('customers/index', ['title' => 'Customers']);
    }

    public function reports(Request $request): string
    {
        return $this->view('admin/reports', ['title' => 'Reports']);
    }
}
