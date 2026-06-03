<?php

namespace App\Services\Orders;

use App\Repositories\OrderListRepository;

class GetOrderListService
{
    public function __construct(private OrderListRepository $orderListRepository = new OrderListRepository()) {}

    /**
     * Execute and return order list. Accepts optional filters.
     *
     * @param array $filters ['term' => string, 'status' => string, 'mechanic_user_id' => int]
     * @return array
     */
    public function execute(array $filters = []): array
    {
        return $this->orderListRepository->findAll($filters);
    }
}
