<?php

namespace App\Services\Orders;

use App\Models\ServiceOrder;
use App\Repositories\OrderCreateRepository;
use App\Services\Customers\CreateCustomerService;
use App\Services\Mechanics\DelegateOrderToMechanicService;
use App\Services\OrderQueue\AddToqueueService;
use App\Services\Vehicles\CreateVehicleService;

class CreateService
{
    public function __construct(
        private OrderCreateRepository $orderCreateRepository = new OrderCreateRepository(),
        private CreateCustomerService $createCustomerService = new CreateCustomerService(),
        private CreateVehicleService $createVehicleService = new CreateVehicleService(),
        private AddToqueueService $addToqueueService = new AddToqueueService(),
        private DelegateOrderToMechanicService $delegateOrderToMechanicService = new DelegateOrderToMechanicService()
    ) {}

    public function execute(ServiceOrder $order, array $data): int
    {
        $customerId = isset($data['customer_id']) ? (int) $data['customer_id'] : 0;

        if ($customerId <= 0) {
            $customerId = $this->createCustomerService->findExistingCustomerId($data['customer_email'] ?? null, $data['customer_phone'] ?? null);
        }

        if ($customerId <= 0) {
            $customerName = trim((string) ($data['customer_name'] ?? ''));
            if ($customerName === '') {
                throw new \RuntimeException('Customer name is required when creating an order.');
            }

            $customerId = $this->createCustomerService->execute([
                'name' => $customerName,
                'phone' => $data['customer_phone'] ?? null,
                'email' => $data['customer_email'] ?? null,
            ]);
        }

        $vehicleId = null;
        $vehicleModel = trim((string) ($data['vehicle_model'] ?? ''));
        
        if ($vehicleModel !== '') {
            $vehicleId = $this->createVehicleService->execute(
                $customerId,
                isset($data['vehicle_brand_id']) && $data['vehicle_brand_id'] !== '' ? (int) $data['vehicle_brand_id'] : null,
                $vehicleModel,
                $data['vehicle_plate'] ?? null,
                isset($data['vehicle_year']) && $data['vehicle_year'] !== '' ? (int) $data['vehicle_year'] : null
            );
        }

        $order->setCustomerId($customerId);
        $order->setVehicleId($vehicleId);
        $orderId = $this->orderCreateRepository->create($order);

        $queueId = $this->addToqueueService->execute($orderId);
        $wasAssigned = $this->delegateOrderToMechanicService->execute($orderId);

        if ($wasAssigned) {
            $this->addToqueueService->markWorking($queueId);
        }

        return $orderId;
    }
}
