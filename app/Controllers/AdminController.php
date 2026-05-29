<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;

class AdminController extends BaseController
{
    public function dashboard(Request $request): string
    {
        return $this->view('admin/dashboard', ['title' => 'Admin Dashboard']);
    }

    public function users(Request $request): string
    {
        return $this->view('admin/users', ['title' => 'Users']);
    }

    public function settings(Request $request): string
    {
        return $this->view('admin/settings', ['title' => 'Settings']);
    }
}
