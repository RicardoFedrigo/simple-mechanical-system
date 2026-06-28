<?php

namespace App\Services\Mechanics;

use App\Repositories\MechanicsRepository;
use App\Repositories\OrderListRepository;
use InvalidArgumentException;

class DelegateOrderToMechanicService
{
    public function __construct(
        private MechanicsRepository $mechanicsRepository = new MechanicsRepository(),
        private OrderListRepository $orderListRepository = new OrderListRepository()
    ) {}

    /**
     * Delegate a service order to a mechanic
     * 
     * @param int $orderId The service order ID to delegate
     * @param int $mechanicId The mechanic ID to assign to
     * @return bool True if delegation was successful
     * @throws InvalidArgumentException If order or mechanic doesn't exist
     */
    public function execute(int $orderId): bool
    {
        // Validate order exists
        $order = $this->orderListRepository->findById($orderId);
        if (!$order) {
            throw new InvalidArgumentException('Service order not found.');
        }

        $mechanicIdSelected = $this->executeWithLoadBalancing($orderId);

        if(empty($mechanicIdSelected)) {
            throw new InvalidArgumentException('No mechanics available for delegation.');
        }



        // Delegate the order by assigning mechanic and updating status to IN_PROGRESS
        return $this->orderListRepository->assignMechanicToOrder($orderId, $mechanicIdSelected);
    }

    /**
     * Delegate a service order to the mechanic with least active orders
     * Useful for automatic load balancing
     * 
     * @param int $orderId The service order ID to delegate
     * @return bool True if delegation was successful
     * @throws InvalidArgumentException If no mechanics available or order doesn't exist
     */
    public function executeWithLoadBalancing(int $orderId): string
    {
        // Validate order exists
        $order = $this->orderListRepository->findById($orderId);
        if (!$order) {
            throw new InvalidArgumentException('Service order not found.');
        }

        // Get mechanics with least active orders
        $mechanicsWithCounts = $this->mechanicsRepository->findMechanicsWithActiveOrdersCount();
        
        if (empty($mechanicsWithCounts)) {
            throw new InvalidArgumentException('No mechanics available.');
        }

        // Assign to mechanic with least active orders
        $selectedMechanicData = $mechanicsWithCounts[0]; // Already sorted by active_orders_count ASC
        $selectedMechanic = $selectedMechanicData['mechanic'];

        return $selectedMechanic->getId();
    }
}
