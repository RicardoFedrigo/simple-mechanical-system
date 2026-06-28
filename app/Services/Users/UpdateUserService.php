<?php

namespace App\Services\Users;

use App\Repositories\UserRepository;
use RuntimeException;

class UpdateUserService
{
    public function __construct(private ?UserRepository $userRepository = null)
    {
        $this->userRepository ??= new UserRepository();
    }

    public function execute(int $userId, array $data): void
    {
        $name = trim((string)($data['name'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));
        $roleId = (int)($data['role_id'] ?? 0);
        $active = !empty($data['active']);

        if ($userId <= 0 || $name === '' || $email === '' || $roleId <= 0) {
            throw new RuntimeException('Name, email and role are required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Invalid email address.');
        }

        if (!$this->userRepository->findById($userId)) {
            throw new RuntimeException('User not found.');
        }

        if (!$this->userRepository->update($userId, $name, $email, $roleId, $active)) {
            throw new RuntimeException('Failed to update user.');
        }
    }
}
