<?php

namespace App\Services\Items;

use App\Repositories\AuditItemRepository;

class AuditItemService
{
    private AuditItemRepository $repository;

    public function __construct()
    {
        $this->repository = new AuditItemRepository();
    }

    public function log(int $itemId, string $actionType, int $quantity, int $actual, string $changedBy): void
    {
        $this->repository->log($itemId, $actionType, $quantity, $actual, $changedBy);
    }
}
