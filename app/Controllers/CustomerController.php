<?php

namespace App\Controllers;
use App\Services\Customers\SearchCustomerService;
use App\Core\Request;

class CustomerController
{
    private SearchCustomerService $searchCustomerService;
    public function __construct() {
        $this->searchCustomerService = new SearchCustomerService();
    }

    public function search(Request $request)
    {
        $query = $request->query();
        $term = trim($query['term'] ?? '');

        if (strlen($term) < 3) {
            http_response_code(400);
            header('Content-Type: application/json');
            return json_encode(['error' => 'Search term must be at least 3 characters long']);
        }

        $customers = $this->searchCustomerService->execute($term);
        $results = array_map(function ($customer) {
            return [
                'id' => $customer['id'],
                'name' => $customer['name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
            ];
        }, $customers);

        header('Content-Type: application/json');
        return json_encode($results);
    }
}
