<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\EntityMapper;
use App\Models\CarBrand;
use PDO;

class CarBrandRepository extends BaseModel
{
    public function findAll(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM car_brands ORDER BY name ASC');
        $stmt->execute();
        $car_brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => EntityMapper::toCarBrand($data), $car_brands ?: []);
    }

    public function findById(int $id): ?CarBrand
    {
        $stmt = $this->db->prepare('SELECT * FROM car_brands WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? EntityMapper::toCarBrand($data) : null;
    }
}
