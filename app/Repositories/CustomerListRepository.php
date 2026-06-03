<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class CustomerListRepository extends BaseModel
{
    /**
     * Return customers with optional filters: 'term' (name/phone/email) and 'vehicle_plate'
     *
     * @param array $filters
     * @return array
     */
    public function findAll(array $filters = []): array
    {
        $sql = '
            SELECT
                c.id AS customer_id,
                c.name AS customer_name,
                c.phone AS customer_phone,
                c.email AS customer_email,
                c.created_at AS customer_created_at,
                v.id AS vehicle_id,
                v.plate_number,
                v.model,
                v.year
            FROM customers c
            LEFT JOIN vehicles v ON v.customer_id = c.id
        ';

        $conditions = [];
        $params = [];

        if (!empty($filters['term'])) {
            $conditions[] = '(c.name LIKE :term OR c.phone LIKE :term OR c.email LIKE :term)';
            $params['term'] = '%' . $filters['term'] . '%';
        }

        if (!empty($filters['vehicle_plate'])) {
            $conditions[] = 'v.plate_number LIKE :plate';
            $params['plate'] = '%' . $filters['vehicle_plate'] . '%';
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY c.name ASC, v.id ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Aggregate vehicles per customer
        $results = [];
        foreach ($rows as $row) {
            $cid = (int)$row['customer_id'];
            if (!isset($results[$cid])) {
                $results[$cid] = [
                    'id' => $cid,
                    'name' => $row['customer_name'],
                    'phone' => $row['customer_phone'],
                    'email' => $row['customer_email'],
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
                ];
            }
        }

        return array_values($results);
    }
}
