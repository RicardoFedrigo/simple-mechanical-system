<?php

namespace App\Repositories;

use App\Core\BaseModel;
use Override;
use PDO;

class OrdersRepository extends BaseModel
{

    public function findById(int $id): ?array
    {
        $query =  $this->db->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findOrdersOpen(): ?array
    {

        $query = $this->db->prepare('SELECT * FROM service_orders so WHERE so.status = \'Open\'');

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
