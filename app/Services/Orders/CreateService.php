<?php

namespace App\Services\Orders;

use App\Repositories\CustomerRepository;
use App\Repositories\OrderCreateRepository;

class CreateService
{
    public function __construct(
        private OrderCreateRepository $orderCreateRepository = new OrderCreateRepository(),
        private CustomerRepository $customerRepository = new CustomerRepository()
    ) {}

    public function execute(array $data): int
    {
        $customerId = isset($data['customer_id']) ? (int) $data['customer_id'] : 0;

        if ($customerId <= 0) {
            $customerId = $this->findExistingCustomerId($data['customer_email'] ?? null, $data['customer_phone'] ?? null);
        }

        if ($customerId <= 0) {
            $customerName = trim((string) ($data['customer_name'] ?? ''));
            if ($customerName === '') {
                throw new \RuntimeException('Customer name is required when creating an order.');
            }

            $customerId = $this->customerRepository->create([
                'name' => $customerName,
                'phone' => $data['customer_phone'] ?? null,
                'email' => $data['customer_email'] ?? null,
            ]);
        }

        $vehicleId = null;
        $vehicleModel = trim((string) ($data['vehicle_model'] ?? ''));
        if ($vehicleModel !== '') {
            $vehicleId = $this->orderCreateRepository->createVehicle(
                $customerId,
                isset($data['vehicle_brand_id']) && $data['vehicle_brand_id'] !== '' ? (int) $data['vehicle_brand_id'] : null,
                $vehicleModel,
                $data['vehicle_plate'] ?? null,
                isset($data['vehicle_year']) && $data['vehicle_year'] !== '' ? (int) $data['vehicle_year'] : null
            );
        }

        $status = trim((string) ($data['status'] ?? 'PENDING')) ?: 'PENDING';
        $subtotal = isset($data['subtotal']) ? (float) $data['subtotal'] : 0.0;
        $tax = isset($data['tax']) ? (float) $data['tax'] : 0.0;
        $total = isset($data['total']) ? (float) $data['total'] : ($subtotal + $tax);

        $orderId = $this->orderCreateRepository->createServiceOrder(
            $customerId,
            $vehicleId,
            null,
            $status,
            $subtotal,
            $tax,
            $total,
        );

        $description = trim((string) ($data['description'] ?? ''));
        if ($description !== '') {
            $this->orderCreateRepository->createServiceOrderItem($orderId, $description, 1, 0.0, 0.0);
        }

        return $orderId;
    }

    private function findExistingCustomerId(?string $email, ?string $phone): int
    {
        $customer = $this->customerRepository->findByEmailOrPhone($email, $phone);

        return $customer['id'] ?? 0;
    }
}
