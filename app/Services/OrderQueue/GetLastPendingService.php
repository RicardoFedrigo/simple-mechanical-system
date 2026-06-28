<?php

namespace App\Services\OrderQueue;

use App\Repositories\OrdersQueueRepository;
use App\Models\OrdersQueue;

class GetLastPendingService
{
    public function __construct(private OrdersQueueRepository $repository = new OrdersQueueRepository()) {}

    public function execute(): ?OrdersQueue
    {
        return $this->repository->getLastPendingOrder();
    }
}
