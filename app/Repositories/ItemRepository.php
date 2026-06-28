<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\Item;
use PDO;

class ItemRepository extends BaseModel
{
    public function search(string $term): array
    {
        if (strlen($term) < 3) {
            return [];
        }

        $query = $this->db->prepare(
            'SELECT * FROM items WHERE name LIKE :term OR sku LIKE :term LIMIT 10'
        );
        $query->execute(['term' => '%' . $term . '%']);

        return array_map(function ($data) {
            return new Item(
                (int)$data['id'],
                $data['name'],
                $data['sku'],
                (int)$data['quantity'],
                (float)$data['unit_price']
            );
        }, $query->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    public function findById(int $itemId): ?Item
    {
        $query = $this->db->prepare('SELECT * FROM items WHERE id = :id');
        $query->execute(['id' => $itemId]);
        $data = $query->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Item(
            (int)$data['id'],
            $data['name'],
            $data['sku'],
            (int)$data['quantity'],
            (float)$data['unit_price']
        );
    }

    public function updateQuantity(int $itemId, int $quantity): bool
    {
        $query = $this->db->prepare('UPDATE items SET quantity = :quantity WHERE id = :id');
        return $query->execute(['quantity' => $quantity, 'id' => $itemId]);
    }
}
