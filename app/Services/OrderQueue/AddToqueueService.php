<?php

namespace App\Services\OrderQueue;

use App\Repositories\OrdersQueueRepository;

class AddToqueueService
{
    public function __construct(private OrdersQueueRepository $repository = new OrdersQueueRepository()) {}

    public function execute(int $orderId): int
    {
        return $this->repository->addToQueue($orderId);
    }

    public function markWorking(int $queueId): bool
    {
        return $this->repository->updateStatus($queueId, 'WORKING');
    }
}
