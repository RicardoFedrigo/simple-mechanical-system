<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderListRepository;
use App\Services\Customers\SearchCustomerService;
use App\Services\Orders\Customers\GetOrdersByCustomerIdService;

class CustomerController extends BaseController
{
    private SearchCustomerService $searchCustomerService;
    private GetOrdersByCustomerIdService $getOrdersByCustomerIdService;
    private CustomerRepository $customerRepository;

    public function __construct() {
        $this->searchCustomerService = new SearchCustomerService();
        $this->getOrdersByCustomerIdService = new GetOrdersByCustomerIdService(new OrderListRepository());
        $this->customerRepository = new CustomerRepository();
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

    public function show(Request $request): string
    {
        $uri = $request->uri();
        preg_match('#/customers/(\d+)#', $uri, $matches);
        $customerId = (int) ($matches[1] ?? 0);

        $customer = $this->customerRepository->findById($customerId);
        if (!$customer) {
            http_response_code(404);
            return $this->view('404', ['message' => 'Customer not found']);
        }

        return $this->view('customers/details', [
            'title' => 'Customer Details',
            'customer' => $customer
        ]);
    }

    public function orders(Request $request): string
    {
        $uri = $request->uri();
        preg_match('#/customers/(\d+)/orders#', $uri, $matches);
        $customerId = (int) ($matches[1] ?? 0);

        $customer = $this->customerRepository->findById($customerId);
        if (!$customer) {
            http_response_code(404);
            return "Customer not found";
        }

        $orders = $this->getOrdersByCustomerIdService->execute($customerId);

        return $this->view('customers/orders', [
            'title' => 'Orders for ' . $customer->getName(),
            'customer' => $customer,
            'orders' => $orders
        ]);
    }
}
