<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class AuditItemRepository extends BaseModel
{
    public function log(int $itemId, string $actionType, int $quantity, int $actual, string $changedBy): void
    {
        $query = $this->db->prepare(
            'INSERT INTO audit_items_parts (part_id, action_type, quantity, actual, changed_by) 
             VALUES (:part_id, :action_type, :quantity, :actual, :changed_by)'
        );
        $query->execute([
            'part_id' => $itemId,
            'action_type' => $actionType,
            'quantity' => $quantity,
            'actual' => $actual,
            'changed_by' => $changedBy
        ]);
    }
}
