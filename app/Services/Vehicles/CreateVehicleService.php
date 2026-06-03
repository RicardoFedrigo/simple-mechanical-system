<?php

namespace App\Services\Vehicles;

use App\Repositories\OrderCreateRepository;

class CreateVehicleService
{
    public function __construct(
        private OrderCreateRepository $orderCreateRepository = new OrderCreateRepository(),
        private ValidatePlateService $validatePlateService = new ValidatePlateService()
    ) {}

    /**
     * Create a vehicle and return its id.
     *
     * @param int $customerId
     * @param int|null $brandId
     * @param string $model
     * @param string|null $plate
     * @param int|null $year
     * @return int
     */
    public function execute(int $customerId, ?int $brandId, string $model, ?string $plate, ?int $year): int
    {
        if ($customerId <= 0) {
            throw new \InvalidArgumentException('Valid customer ID is required to create a vehicle.');
        }

        $validatedPlate = $this->validatePlateService->execute($plate);

        return $this->orderCreateRepository->createVehicle(
            $customerId,
            $brandId,
            $model,
            $validatedPlate,
            $year
        );
    }
}
