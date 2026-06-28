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
    public function execute(array $filters = [], int $page = 1, int $limit = 20): array
    {
        $offset = ($page - 1) * $limit;
        return $this->orderListRepository->findAll($filters, $limit, $offset);
    }

    public function count(array $filters = []): int
    {
        return $this->orderListRepository->count($filters);
    }
}
