<?php

namespace App\Services\Users;

use App\Repositories\UserRepository;
use RuntimeException;

class ActivateUserService
{
    public function __construct(private ?UserRepository $userRepository = null)
    {
        $this->userRepository ??= new UserRepository();
    }

    public function execute(int $userId): void
    {
        if ($userId <= 0) {
            throw new RuntimeException('Invalid user.');
        }

        if (!$this->userRepository->findById($userId)) {
            throw new RuntimeException('User not found.');
        }

        if (!$this->userRepository->activate($userId)) {
            throw new RuntimeException('Failed to activate user.');
        }
    }
}
