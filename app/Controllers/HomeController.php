<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Services\Customers\GetCustomerListService;

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
        $query = $request->query();
        $filters = [
            'term' => trim($query['term'] ?? ''),
            'vehicle_plate' => trim($query['vehicle_plate'] ?? ''),
        ];

        $service = new GetCustomerListService();
        $customers = $service->execute(array_filter($filters));

        return $this->view('customers/index', ['title' => 'Customers', 'customers' => $customers, 'filters' => $filters]);
    }

    public function reports(Request $request): string
    {
        return $this->view('admin/reports', ['title' => 'Reports']);
    }
}
