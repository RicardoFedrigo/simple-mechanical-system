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

        $order = $this->orderListRepository->findById($orderId);
        if (!$order) {
            throw new \InvalidArgumentException('Order not found.');
        }

        if ($order->getStatus() === 'COMPLETED') {
            throw new \InvalidArgumentException('Cannot change status of a completed order.');
        }

        if ($order->getStatus() === $status) {
            return true;
        }

        // Logic: PENDING -> IN_PROGRESS -> COMPLETED
        if ($order->getStatus() === 'PENDING' && $status !== 'IN_PROGRESS') {
             throw new \InvalidArgumentException('A pending order can only be changed to IN_PROGRESS.');
        }

        if ($order->getStatus() === 'IN_PROGRESS' && $status !== 'COMPLETED') {
             throw new \InvalidArgumentException('An order in progress can only be changed to COMPLETED.');
        }

        return $this->orderListRepository->updateStatus($orderId, $status);
    }
}
