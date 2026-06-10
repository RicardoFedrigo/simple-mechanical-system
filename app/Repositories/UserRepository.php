<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\EntityMapper;
use App\Models\User;
use PDO;

class UserRepository extends BaseModel
{
    public function findByEmail(string $email): ?User
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

        return $user ? EntityMapper::toUser($user) : null;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? EntityMapper::toUser($data) : null;
    }
}
