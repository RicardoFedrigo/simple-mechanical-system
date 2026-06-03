<?php

namespace App\Services\Orders;

use App\Repositories\OrderListRepository;

class GetOrderByIdService
{
    public function __construct(private OrderListRepository $orderListRepository = new OrderListRepository()) {}

    public function execute(int $id): ?array
    {
        return $this->orderListRepository->findById($id);
    }

    public function items(int $orderId): array
    {
        return $this->orderListRepository->findItemsByOrderId($orderId);
    }
}
