<?php

namespace App\Services\Customers;

use App\Repositories\CustomerListRepository;

class GetCustomerListService
{
    public function __construct(private CustomerListRepository $customerListRepository = new CustomerListRepository()) {}

    /**
     * Execute and return customer list. Accepts optional filters.
     *
     * @param array $filters ['term' => string, 'vehicle_plate' => string]
     * @return array
     */
    public function execute(array $filters = []): array
    {
        return $this->customerListRepository->findAll($filters);
    }
}
