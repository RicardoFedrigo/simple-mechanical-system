<?php

namespace App\Services\Orders\Customers;

use App\Repositories\OrdersRepository;

class GetOrdersByCustomerIdService
{
    private OrdersRepository $repository;

    public function __construct(OrdersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $customerId): array
    {
        return $this->repository->findByCustomerId($customerId);
    }
}
