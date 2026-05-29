<?php

namespace App\Core;

use PDO;

abstract class BaseModel
{
    protected PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? Database::getConnection();
    }
}
