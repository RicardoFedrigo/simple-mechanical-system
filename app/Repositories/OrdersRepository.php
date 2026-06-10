<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\EntityMapper;
use App\Models\ServiceOrder;
use PDO;

class OrdersRepository extends BaseModel
{
    public function findById(int $id): ?ServiceOrder
    {
        $query = $this->db->prepare(
            'SELECT * FROM service_orders WHERE id = :id LIMIT 1'
        );
        $query->execute(['id' => $id]);

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data ? EntityMapper::toServiceOrder($data) : null;
    }

    public function findOrdersOpen(): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM service_orders WHERE status = :status'
        );
        $query->execute(['status' => 'Open']);

        return array_map(
            fn($data) => EntityMapper::toServiceOrder($data),
            $query->fetchAll(PDO::FETCH_ASSOC) ?: []
        );
    }
}
