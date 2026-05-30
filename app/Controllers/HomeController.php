<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;

class HomeController extends BaseController
{
    public function dashboard(Request $request)
    {

        $userRole = $_SESSION['user']['role'] ?? '';

        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        switch ($userRole) {
            case 'Admin':
                return $this->view('dashboard/Admin', [
                    'title' => 'Admin Dashboard',
                    'user' => $_SESSION['user'] ?? [],
                ]);
            case 'Mechanic':
                return $this->view('dashboard/Mechanic', [
                    'title' => 'Mechanic Dashboard',
                    'user' => $_SESSION['user'] ?? [],
                ]);
            case 'Attendant':
                return $this->view('dashboard/Attendant', [
                    'title' => 'Attendant Dashboard',
                    'user' => $_SESSION['user'] ?? [],
                ]);
            default:
                $this->redirect('/login');
        }
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
