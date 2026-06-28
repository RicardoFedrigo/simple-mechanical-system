<?php

namespace App\Services\Users;

use App\Repositories\UserRepository;
use RuntimeException;

class DeactivateUserService
{
    public function __construct(private ?UserRepository $userRepository = null)
    {
        $this->userRepository ??= new UserRepository();
    }

    public function execute(int $userId, ?int $currentUserId = null): void
    {
        if ($userId <= 0) {
            throw new RuntimeException('Invalid user.');
        }

        if ($currentUserId !== null && $userId === $currentUserId) {
            throw new RuntimeException('You cannot deactivate your own user.');
        }

        if (!$this->userRepository->findById($userId)) {
            throw new RuntimeException('User not found.');
        }

        if (!$this->userRepository->deactivate($userId)) {
            throw new RuntimeException('Failed to deactivate user.');
        }
    }
}
