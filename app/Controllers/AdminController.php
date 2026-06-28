<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Repositories\UserRepository;
use App\Services\Users\ActivateUserService;
use App\Services\Users\DeactivateUserService;
use App\Services\Users\UpdateUserService;

class AdminController extends BaseController
{
    private UserRepository $userRepository;
    private DeactivateUserService $deactivateUserService;
    private ActivateUserService $activateUserService;
    private UpdateUserService $updateUserService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->deactivateUserService = new DeactivateUserService($this->userRepository);
        $this->activateUserService = new ActivateUserService($this->userRepository);
        $this->updateUserService = new UpdateUserService($this->userRepository);
    }

    public function users(Request $request): string
    {
        return $this->view('admin/users', [
            'title' => 'Users',
            'users' => $this->userRepository->findAll(),
            'currentUserId' => $_SESSION['user']['id'] ?? null,
            'success' => flash('success'),
            'error' => flash('error'),
        ]);
    }

    public function userDetails(Request $request): string
    {
        $id = $this->getUserIdFromUri($request->uri());
        $user = $this->userRepository->findById($id);

        if (!$user) {
            http_response_code(404);
            return $this->view('404', ['message' => 'User not found']);
        }

        return $this->view('admin/user_details', [
            'title' => 'User Details',
            'user' => $user,
            'currentUserId' => $_SESSION['user']['id'] ?? null,
        ]);
    }

    public function editUser(Request $request): string
    {
        $id = $this->getUserIdFromUri($request->uri());
        $user = $this->userRepository->findById($id);

        if (!$user) {
            http_response_code(404);
            return $this->view('404', ['message' => 'User not found']);
        }

        return $this->view('admin/user_edit', [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $this->userRepository->findAllRoles(),
            'error' => flash('error'),
        ]);
    }

    public function updateUser(Request $request): void
    {
        $id = $this->getUserIdFromUri($request->uri());

        try {
            $this->updateUserService->execute($id, [
                'name' => $request->input('name', ''),
                'email' => $request->input('email', ''),
                'role_id' => $request->input('role_id', 0),
                'active' => $request->input('active', '0') === '1',
            ]);
            flash('success', 'User updated successfully.');
            $this->redirect('/admin/users/' . $id);
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
            $this->redirect('/admin/users/' . $id . '/edit');
        }
    }

    public function deactivateUser(Request $request): void
    {
        $id = $this->getUserIdFromUri($request->uri());

        try {
            $this->deactivateUserService->execute($id, $_SESSION['user']['id'] ?? null);
            flash('success', 'User deactivated successfully.');
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }

        $this->redirect('/admin/users');
    }

    public function activateUser(Request $request): void
    {
        $id = $this->getUserIdFromUri($request->uri());

        try {
            $this->activateUserService->execute($id);
            flash('success', 'User activated successfully.');
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }

        $this->redirect('/admin/users');
    }

    private function getUserIdFromUri(string $uri): int
    {
        preg_match('#/admin/users/(\d+)#', $uri, $matches);
        return (int)($matches[1] ?? 0);
    }
}
