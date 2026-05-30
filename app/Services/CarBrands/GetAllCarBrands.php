<?php

namespace App\Services\CarBrands;

use App\Repositories\CarBrandRepository;

class GetAllCarBrands
{

    private CarBrandRepository $carBrandRepository;

    public function __construct()
    {
        $this->carBrandRepository = new CarBrandRepository();
    }

    public function execute(): array
    {
        return $this->carBrandRepository->findAll();
    }
}
