<?php

namespace App\Services;

use App\Repositories\UserRepository;

class AuthService
{
    public function __construct(private UserRepository $userRepository) {}

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !password_verify($password, $user->getPassword())) {
            return false;
        }

        $role = $user->getRole()?->getName() ?? '';

        $_SESSION['user'] = [
            'id' => (int) $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'role' => $role,
        ];

        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }
}
