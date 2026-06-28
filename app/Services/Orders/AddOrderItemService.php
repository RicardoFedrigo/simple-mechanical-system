<?php

namespace App\Services\Orders;

use App\Repositories\ServiceOrderItemRepository;
use App\Repositories\OrderListRepository;
use App\Services\Items\AuditItemService;
use Exception;

class AddOrderItemService
{
    private ServiceOrderItemRepository $itemRepository;
    private OrderListRepository $orderRepository;
    private AuditItemService $auditService;

    public function __construct()
    {
        $this->itemRepository = new ServiceOrderItemRepository();
        $this->orderRepository = new OrderListRepository();
        $this->auditService = new AuditItemService();
    }

    public function execute(int $orderId, int $mechanicUserId, int $itemId, int $quantity, float $unitPrice): void
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new Exception("Order not found.");
        }

        $mechanicId = $order->getMechanic()?->getUserId() ?? null;

        if ($mechanicId !== $mechanicUserId) {
            throw new Exception("Unauthorized: You are not the mechanic assigned to this order.");
        }

        

        $isAddItem =$this->itemRepository->create($orderId, $itemId, $quantity, $unitPrice);

        if (!$isAddItem) {
            throw new Exception("Failed to add item to order.");
        }

        $this->auditService->log($itemId,"USED ITEM", $quantity, $mechanicUserId, "Item added to order #$orderId",);

    }
}
