<?php

namespace App\Services\Mechanics;

use App\Repositories\MechanicsRepository;
use App\Models\Mechanic;

class GetMechanicsWithoutServiceService
{
    public function __construct(private MechanicsRepository $mechanicsRepository = new MechanicsRepository()) {}

    /**
     * Execute and return all mechanics without active service orders
     * Filters mechanics that don't have PENDING or IN_PROGRESS orders
     *
        * @return Mechanic[] Array of available mechanics
     */
    public function execute(): array
    {
        return $this->mechanicsRepository->findMechanicsWithoutActiveService();
    }

    /**
     * Get mechanics with a count of their active orders
     * Useful for load balancing when assigning work
     *
     * @return array Array of mechanics with active_orders_count
     */
    public function getWithActiveOrdersCount(): array
    {
        return $this->mechanicsRepository->findMechanicsWithActiveOrdersCount();
    }
}
