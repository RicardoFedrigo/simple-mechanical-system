<?php

namespace App\Services\Orders\Customers;

use App\Repositories\OrderListRepository;

class GetOrdersByCustomerIdService
{
    private OrderListRepository $repository;

    public function __construct(OrderListRepository $repository = new OrderListRepository())
    {
        $this->repository = $repository;
    }

    public function execute(int $customerId): array
    {
        return $this->repository->findAll(['customer_id' => $customerId]);
    }
}
