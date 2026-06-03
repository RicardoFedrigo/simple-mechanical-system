<?php

namespace App\Services\Customers;

use App\Repositories\CustomerRepository;

class SearchCustomerService
{
    private CustomerRepository $customerRepository;

    public function __construct()
    {
        $this->customerRepository = new CustomerRepository();
    }

    public function execute(string $term): array
    {
        return $this->customerRepository->searchWithVehicles($term);
    }
}
