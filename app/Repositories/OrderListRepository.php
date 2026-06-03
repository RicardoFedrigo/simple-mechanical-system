<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class OrderListRepository extends BaseModel
{
    public function findAll(array $filters = []): array
    {
        // Supports optional filters: ['term' => string, 'status' => string, 'mechanic_user_id' => int]
        $sql = "SELECT
                so.id,
                so.status,
                so.subtotal,
                so.tax,
                so.total,
                so.created_at,
                c.name AS customer_name,
                v.model AS vehicle_model,
                v.plate_number
            FROM service_orders so
            LEFT JOIN customers c ON c.id = so.customer_id
            LEFT JOIN vehicles v ON v.id = so.vehicle_id
            LEFT JOIN mechanics m ON m.id = so.mechanic_id
        ";

        $conditions = [];
        $params = [];

        // allow searching by customer name (partial)
        if (!empty($filters['term'])) {
            $conditions[] = 'c.name LIKE :term';
            $params['term'] = '%' . $filters['term'] . '%';
        }

        if (!empty($filters['status'])) {
            $conditions[] = 'so.status = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['mechanic_user_id'])) {
            $conditions[] = 'm.user_id = :mechanic_user_id';
            $params['mechanic_user_id'] = $filters['mechanic_user_id'];
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY so.created_at DESC';

        $query = $this->db->prepare($sql);
        $query->execute($params);

        return $query->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?array
    {
        $query = $this->db->prepare(
            'SELECT
                so.*,
                c.name AS customer_name,
                c.phone AS customer_phone,
                c.email AS customer_email,
                v.plate_number,
                v.model AS vehicle_model,
                v.year AS vehicle_year,
                m.name AS mechanic_name,
                m.user_id AS mechanic_user_id
            FROM service_orders so
            LEFT JOIN customers c ON c.id = so.customer_id
            LEFT JOIN vehicles v ON v.id = so.vehicle_id
            LEFT JOIN mechanics m ON m.id = so.mechanic_id
            WHERE so.id = :id
            LIMIT 1'
        );
        $query->execute(['id' => $id]);

        return $query->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findItemsByOrderId(int $orderId): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM service_order_items WHERE service_order_id = :order_id ORDER BY id ASC'
        );
        $query->execute(['order_id' => $orderId]);

        return $query->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function updateStatus(int $id, string $status): bool
    {
        $query = $this->db->prepare(
            'UPDATE service_orders SET status = :status WHERE id = :id'
        );

        return $query->execute(['status' => $status, 'id' => $id]);
    }
}
