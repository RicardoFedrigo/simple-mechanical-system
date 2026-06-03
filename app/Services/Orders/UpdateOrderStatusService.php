<?php

namespace App\Services\Orders;

use App\Repositories\OrderListRepository;

class UpdateOrderStatusService
{
    private const ALLOWED_STATUSES = ['PENDING', 'IN_PROGRESS', 'COMPLETED'];

    public function __construct(private OrderListRepository $orderListRepository = new OrderListRepository()) {}

    public function execute(int $orderId, string $status): bool
    {
        $status = strtoupper(trim($status));
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw new \InvalidArgumentException('Invalid order status.');
        }

        return $this->orderListRepository->updateStatus($orderId, $status);
    }
}
