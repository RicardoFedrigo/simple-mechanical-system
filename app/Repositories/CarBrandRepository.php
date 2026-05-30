<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class CarBrandRepository extends BaseModel
{
    public function findAll(): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM car_brands ORDER BY name ASC');
        $stmt->execute();
        $car_brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $car_brands ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM car_brands WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
