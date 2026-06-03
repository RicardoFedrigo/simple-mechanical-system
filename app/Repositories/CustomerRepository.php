<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class CustomerRepository extends BaseModel
{
    /**
     * Search customers and include their vehicles and car brand when available.
     *
     * @param string $term
     * @return array
     */
    public function searchWithVehicles(string $term): array
    {
        $like = '%' . $term . '%';
        $sql = '
            SELECT
                c.id AS customer_id,
                c.name AS customer_name,
                c.phone AS customer_phone,
                c.email AS customer_email,
                c.notes AS customer_notes,
                c.created_at AS customer_created_at,
                v.id AS vehicle_id,
                v.plate_number,
                v.model,
                v.year,
                v.status,
                cb.id AS car_brand_id,
                cb.name AS car_brand_name
            FROM customers c
            LEFT JOIN vehicles v ON v.customer_id = c.id
            LEFT JOIN car_brands cb ON cb.id = v.car_brand_id
            WHERE c.name LIKE :term
            ORDER BY c.name ASC, v.id ASC
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['term' => $like]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($rows as $row) {
            $cid = (int)$row['customer_id'];
            if (!isset($results[$cid])) {
                $results[$cid] = [
                    'id' => $cid,
                    'name' => $row['customer_name'],
                    'phone' => $row['customer_phone'],
                    'email' => $row['customer_email'],
                    'notes' => $row['customer_notes'],
                    'created_at' => $row['customer_created_at'],
                    'vehicles' => [],
                ];
            }
            if (!empty($row['vehicle_id'])) {
                $results[$cid]['vehicles'][] = [
                    'id' => (int)$row['vehicle_id'],
                    'plate_number' => $row['plate_number'],
                    'model' => $row['model'],
                    'year' => $row['year'] !== null ? (int)$row['year'] : null,
                    'status' => $row['status'],
                    'car_brand' => $row['car_brand_id'] ? [
                        'id' => (int)$row['car_brand_id'],
                        'name' => $row['car_brand_name'],
                    ] : null,
                ];
            }
        }

        return array_values($results);
    }

    public function findByEmailOrPhone(?string $email, ?string $phone): ?array
    {
        if (empty($email) && empty($phone)) {
            return null;
        }

        $sql = 'SELECT * FROM customers WHERE';
        $params = [];
        $conditions = [];

        if (!empty($email)) {
            $conditions[] = 'email = :email';
            $params['email'] = $email;
        }

        if (!empty($phone)) {
            $conditions[] = 'phone = :phone';
            $params['phone'] = $phone;
        }

        $sql .= ' ' . implode(' OR ', $conditions) . ' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO customers (name, phone, email) VALUES (:name, :phone, :email)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT id, name, phone, email FROM customers ORDER BY name ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
