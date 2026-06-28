<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\EntityMapper;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use PDO;

class OrderListRepository extends BaseModel
{
    public function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*)
            FROM service_orders so
            LEFT JOIN customers c ON c.id = so.customer_id
            LEFT JOIN vehicles v ON v.id = so.vehicle_id
            LEFT JOIN mechanics m ON m.id = so.mechanic_id
        ";

        $conditions = [];
        $params = [];

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

        if (!empty($filters['customer_id'])) {
            $conditions[] = 'so.customer_id = :customer_id';
            $params['customer_id'] = $filters['customer_id'];
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $query = $this->db->prepare($sql);
        $query->execute($params);

        return (int)$query->fetchColumn();
    }

    public function findAll(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        // Supports optional filters: ['term' => string, 'status' => string, 'mechanic_user_id' => int]
        $sql = "SELECT
                so.id,
                so.status,
                so.subtotal,
                so.tax,
                so.total,
                so.created_at,
                so.updated_at,
                so.customer_id,
                so.vehicle_id,
                so.mechanic_id,
                c.id AS customer_id,
                c.name AS customer_name,
                c.phone AS customer_phone,
                c.email AS customer_email,
                c.created_at AS customer_created_at,
                c.updated_at AS customer_updated_at,
                v.id AS vehicle_id,
                v.customer_id AS vehicle_customer_id,
                v.car_brand_id AS vehicle_car_brand_id,
                v.plate_number,
                v.model AS vehicle_model,
                v.year AS vehicle_year,
                v.status AS vehicle_status,
                v.created_at AS vehicle_created_at,
                v.updated_at AS vehicle_updated_at,
                m.id AS mechanic_id,
                m.name AS mechanic_name,
                m.specialty AS mechanic_specialty,
                m.phone AS mechanic_phone,
                m.user_id AS mechanic_user_id
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

        if (!empty($filters['customer_id'])) {
            $conditions[] = 'so.customer_id = :customer_id';
            $params['customer_id'] = $filters['customer_id'];
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY so.created_at DESC LIMIT :limit OFFSET :offset';

        $query = $this->db->prepare($sql);
        $params['limit'] = (int)$limit;
        $params['offset'] = (int)$offset;
        
        foreach ($params as $key => $value) {
            $query->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $query->execute();

        return array_map(function ($data) {
            $customer = EntityMapper::toCustomer([
                'id' => $data['customer_id'] ?? 0,
                'name' => $data['customer_name'] ?? '',
                'phone' => $data['customer_phone'] ?? null,
                'email' => $data['customer_email'] ?? null,
                'created_at' => $data['customer_created_at'] ?? '',
                'updated_at' => $data['customer_updated_at'] ?? '',
            ]);

            $vehicle = null;
            if (!empty($data['vehicle_id'])) {
                $vehicle = EntityMapper::toVehicle([
                    'id' => $data['vehicle_id'],
                    'customer_id' => $data['vehicle_customer_id'] ?? 0,
                    'car_brand_id' => $data['vehicle_car_brand_id'] ?? null,
                    'plate_number' => $data['plate_number'] ?? '',
                    'model' => $data['vehicle_model'] ?? '',
                    'year' => $data['vehicle_year'] ?? null,
                    'status' => $data['vehicle_status'] ?? 'ENTERED',
                    'created_at' => $data['vehicle_created_at'] ?? '',
                    'updated_at' => $data['vehicle_updated_at'] ?? '',
                ]);
            }

            $mechanic = null;
            if (!empty($data['mechanic_id'])) {
                $mechanic = EntityMapper::toMechanic([
                    'id' => $data['mechanic_id'],
                    'name' => $data['mechanic_name'] ?? '',
                    'specialty' => $data['mechanic_specialty'] ?? null,
                    'phone' => $data['mechanic_phone'] ?? null,
                    'user_id' => $data['mechanic_user_id'] ?? 0,
                ]);
            }

            return EntityMapper::toServiceOrder($data, $customer, $vehicle, $mechanic);
        }, $query->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    public function findById(int $id): ?ServiceOrder
    {
        $query = $this->db->prepare(
            'SELECT
                so.*, 
                c.id AS customer_id,
                c.name AS customer_name,
                c.phone AS customer_phone,
                c.email AS customer_email,
                c.created_at AS customer_created_at,
                c.updated_at AS customer_updated_at,
                v.id AS vehicle_id,
                v.customer_id AS vehicle_customer_id,
                v.car_brand_id AS vehicle_car_brand_id,
                v.plate_number,
                v.model AS vehicle_model,
                v.year AS vehicle_year,
                v.status AS vehicle_status,
                v.created_at AS vehicle_created_at,
                v.updated_at AS vehicle_updated_at,
                m.id AS mechanic_id,
                m.name AS mechanic_name,
                m.specialty AS mechanic_specialty,
                m.phone AS mechanic_phone,
                m.user_id AS mechanic_user_id
            FROM service_orders so
            LEFT JOIN customers c ON c.id = so.customer_id
            LEFT JOIN vehicles v ON v.id = so.vehicle_id
            LEFT JOIN mechanics m ON m.id = so.mechanic_id
            WHERE so.id = :id
            LIMIT 1'
        );
        $query->execute(['id' => $id]);

        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        $customer = EntityMapper::toCustomer([
            'id' => $data['customer_id'] ?? 0,
            'name' => $data['customer_name'] ?? '',
            'phone' => $data['customer_phone'] ?? null,
            'email' => $data['customer_email'] ?? null,
            'created_at' => $data['customer_created_at'] ?? '',
            'updated_at' => $data['customer_updated_at'] ?? '',
        ]);

        $vehicle = null;
        if (!empty($data['vehicle_id'])) {
            $vehicle = EntityMapper::toVehicle([
                'id' => $data['vehicle_id'],
                'customer_id' => $data['vehicle_customer_id'] ?? 0,
                'car_brand_id' => $data['vehicle_car_brand_id'] ?? null,
                'plate_number' => $data['plate_number'] ?? '',
                'model' => $data['vehicle_model'] ?? '',
                'year' => $data['vehicle_year'] ?? null,
                'status' => $data['vehicle_status'] ?? 'ENTERED',
                'created_at' => $data['vehicle_created_at'] ?? '',
                'updated_at' => $data['vehicle_updated_at'] ?? '',
            ]);
        }

        $mechanic = null;
        if (!empty($data['mechanic_id'])) {
            $mechanic = EntityMapper::toMechanic([
                'id' => $data['mechanic_id'],
                'name' => $data['mechanic_name'] ?? '',
                'specialty' => $data['mechanic_specialty'] ?? null,
                'phone' => $data['mechanic_phone'] ?? null,
                'user_id' => $data['mechanic_user_id'] ?? 0,
            ]);
        }

        return EntityMapper::toServiceOrder($data, $customer, $vehicle, $mechanic);
    }

    public function findItemsByOrderId(int $orderId): array
    {
        $query = $this->db->prepare(
            'SELECT soi.*, i.name, i.sku, i.unit_price 
             FROM service_order_items soi
             JOIN items i ON i.id = soi.items_id
             WHERE soi.service_order_id = :order_id ORDER BY soi.id ASC'
        );
        $query->execute(['order_id' => $orderId]);

        return array_map(function ($data) {
            $item = EntityMapper::toItem([
                'id' => $data['items_id'],
                'name' => $data['name'],
                'sku' => $data['sku'],
                'unit_price' => $data['unit_price']
            ]);
            return EntityMapper::toServiceOrderItem($data, $item);
        }, $query->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $query = $this->db->prepare(
            'UPDATE service_orders SET status = :status WHERE id = :id'
        );

        return $query->execute(['status' => $status, 'id' => $id]);
    }

    /**
     * Assign a mechanic to a service order
     *
     * @param int $orderId
     * @param int $mechanicId
     * @return bool
     */
    public function assignMechanicToOrder(int $orderId, int $mechanicId): bool
    {
        $query = $this->db->prepare(
            'UPDATE service_orders SET mechanic_id = :mechanic_id, status = :status WHERE id = :id'
        );

        return $query->execute([
            'mechanic_id' => $mechanicId,
            'status' => 'IN_PROGRESS',
            'id' => $orderId
        ]);
    }
}
