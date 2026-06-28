<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class ServiceOrderItemRepository extends BaseModel
{
    public function create(int $orderId, int $itemId, int $quantity, float $unitPrice): bool
    {
        $total = $quantity * $unitPrice;
        $query = $this->db->prepare(
            'INSERT INTO service_order_items (service_order_id, items_id, quantity, total) 
             VALUES (:order_id, :item_id, :quantity, :total)'
        );
        $result = $query->execute([
            'order_id' => $orderId,
            'item_id' => $itemId,
            'quantity' => $quantity,
            'total' => $total
        ]);

        if ($result) {
            $this->updateOrderTotal($orderId);
        }

        return $result;
    }

    private function updateOrderTotal(int $orderId): void
    {
        $query = $this->db->prepare(
            'SELECT SUM(soi.quantity * i.unit_price) as subtotal 
             FROM service_order_items soi
             JOIN items i ON i.id = soi.items_id
             WHERE soi.service_order_id = :order_id'
        );
        $query->execute(['order_id' => $orderId]);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $subtotal = (float)($data['subtotal'] ?? 0);
        $tax = $subtotal * 0.15; // Assuming 15% tax
        $total = $subtotal + $tax;

        $updateQuery = $this->db->prepare('UPDATE service_orders SET subtotal = :subtotal, tax = :tax, total = :total WHERE id = :id');
        $updateQuery->execute([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'id' => $orderId
        ]);
    }
}
