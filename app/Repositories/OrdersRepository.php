<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class OrdersRepository extends BaseModel
{
    public function findById(int $id): ?array
    {
        $query = $this->db->prepare(
            'SELECT * FROM service_orders WHERE id = :id LIMIT 1'
        );
        $query->execute(['id' => $id]);

        return $query->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findOrdersOpen(): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM service_orders WHERE status = :status'
        );
        $query->execute(['status' => 'Open']);

        return $query->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
