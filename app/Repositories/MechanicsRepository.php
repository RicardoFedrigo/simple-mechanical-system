<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\EntityMapper;
use App\Models\Mechanic;
use App\Models\ServiceOrder;
use PDO;

class MechanicsRepository extends BaseModel
{
    /**
     * Find all mechanics without active service orders (PENDING or IN_PROGRESS)
     * 
     * @return Mechanic[]
     */
    public function findMechanicsWithoutActiveService(): array
    {
        $query = $this->db->prepare("
            SELECT 
                m.id,
                m.name,
                m.specialty,
                m.phone,
                m.created_at,
                u.id as user_id,
                u.email,
                u.name as user_name
            FROM mechanics m
            JOIN users u ON m.user_id = u.id
            WHERE m.id NOT IN (
                SELECT DISTINCT mechanic_id 
                FROM service_orders 
                WHERE status IN ('PENDING', 'IN_PROGRESS') 
                AND mechanic_id IS NOT NULL
            )
            ORDER BY m.name ASC
        ");
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(fn($data) => EntityMapper::toMechanic($data), $results);
    }

    /**
     * Find all mechanics with count of active service orders
     * 
     * @return array Array with Mechanic object and active_orders_count
     */
    public function findMechanicsWithActiveOrdersCount(): array
    {
        $query = $this->db->prepare("
            SELECT 
                m.id,
                m.name,
                m.specialty,
                m.phone,
                m.created_at,
                u.id as user_id,
                u.email,
                COUNT(so.id) as active_orders_count
            FROM mechanics m
            JOIN users u ON m.user_id = u.id
            LEFT JOIN service_orders so ON m.id = so.mechanic_id 
                AND so.status IN ('PENDING', 'IN_PROGRESS')
            GROUP BY m.id, m.name, m.specialty, m.phone, m.created_at, u.id, u.email
            ORDER BY active_orders_count ASC, m.name ASC
        ");
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(function($data) {
            $count = $data['active_orders_count'];
            unset($data['active_orders_count']);
            return [
                'mechanic' => EntityMapper::toMechanic($data),
                'active_orders_count' => (int)$count
            ];
        }, $results);
    }

    /**
     * Find a specific mechanic by ID
     * 
     * @param int $id
     * @return Mechanic|null
     */
    public function findById(int $id): ?Mechanic
    {
        $query = $this->db->prepare("
            SELECT 
                m.id,
                m.name,
                m.specialty,
                m.phone,
                m.created_at,
                m.updated_at,
                u.id as user_id,
                u.email,
                u.name as user_name
            FROM mechanics m
            JOIN users u ON m.user_id = u.id
            WHERE m.id = :id
            LIMIT 1
        ");
        $query->execute(['id' => $id]);
        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data ? EntityMapper::toMechanic($data) : null;
    }

    /**
     * Get active service orders for a specific mechanic
     * 
     * @param int $mechanicId
     * @return ServiceOrder[]
     */
    public function getActiveServiceOrders(int $mechanicId): array
    {
        $query = $this->db->prepare("
            SELECT 
                so.id,
                so.status,
                so.created_at,
                so.customer_id,
                so.vehicle_id,
                so.mechanic_id,
                c.name as customer_name,
                v.plate_number,
                v.model as vehicle_model
            FROM service_orders so
            LEFT JOIN customers c ON c.id = so.customer_id
            LEFT JOIN vehicles v ON v.id = so.vehicle_id
            WHERE so.mechanic_id = :mechanic_id
            AND so.status IN ('PENDING', 'IN_PROGRESS')
            ORDER BY so.created_at DESC
        ");
        $query->execute(['mechanic_id' => $mechanicId]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(fn($data) => EntityMapper::toServiceOrder($data), $results);
    }
}
