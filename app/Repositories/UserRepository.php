<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class UserRepository extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT
	                                    u.id ,
                                        u.email ,
                                        u.name,
                                        u.password_hash,
                                        r.name as 'role'
                                    FROM
                                        users u
                                    JOIN roles r ON
                                        u.role_id = r.id
                                    WHERE u.email = :email
                                    AND u.active = 1
                                    LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
