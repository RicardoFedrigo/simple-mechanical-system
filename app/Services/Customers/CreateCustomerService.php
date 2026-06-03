<?php

namespace App\Services\Customers;

use App\Repositories\CustomerRepository;

class CreateCustomerService
{
    public function __construct(private CustomerRepository $customerRepository = new CustomerRepository()) {}

    public function findExistingCustomerId(?string $email, ?string $phone): int
    {
        $customer = $this->customerRepository->findByEmailOrPhone($email, $phone);
        return $customer['id'] ?? 0;
    }

    /**
     * Create a customer record and return its id.
     * Expects array with keys: name, phone, email
     */
    public function execute(array $data): int
    {
        $name = trim((string) ($data['name'] ?? ''));
        if ($name === '') {
            throw new \InvalidArgumentException('Customer name is required');
        }

        if(empty($data['email']) && empty($data['phone'])) {
            throw new \InvalidArgumentException('At least one contact method (email or phone) is required to create a customer.');
        }

        return $this->customerRepository->create([
            'name' => $name,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
        ]);
    }
}
