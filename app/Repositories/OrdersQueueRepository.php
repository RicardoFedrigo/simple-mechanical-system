<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\EntityMapper;
use App\Models\OrdersQueue;
use PDO;

class OrdersQueueRepository extends BaseModel
{
    public function getLastPendingOrder(): ?OrdersQueue
    {
        $query = $this->db->prepare(
            "SELECT * FROM orders_queue WHERE status = :status ORDER BY id ASC LIMIT 1"
        );
        $query->execute(['status' => 'PENDING']);

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data ? EntityMapper::toOrdersQueue($data) : null;
    }

    public function addToQueue(int $orderId): int
    {
        $query = $this->db->prepare(
            "INSERT INTO orders_queue (order_id, status) VALUES (:order_id, :status)"
        );
        $query->execute(['order_id' => $orderId, 'status' => 'PENDING']);

        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $queueId, string $status): bool
    {
        $query = $this->db->prepare(
            "UPDATE orders_queue SET status = :status WHERE id = :id"
        );
        return $query->execute(['status' => $status, 'id' => $queueId]);
    }
}
