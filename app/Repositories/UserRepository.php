<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\EntityMapper;
use App\Models\Role;
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
                                        u.role_id,
                                        u.active,
                                        u.created_at,
                                        u.updated_at,
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
        $stmt = $this->db->prepare("SELECT
                                        u.*,
                                        r.name as 'role'
                                    FROM users u
                                    JOIN roles r ON u.role_id = r.id
                                    WHERE u.id = :id
                                    LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? EntityMapper::toUser($data) : null;
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->prepare("SELECT
                                        u.*,
                                        r.name as 'role'
                                    FROM users u
                                    JOIN roles r ON u.role_id = r.id
                                    ORDER BY u.name ASC");
        $stmt->execute();

        return array_map(
            fn($data) => EntityMapper::toUser($data),
            $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []
        );
    }

    /**
     * @return Role[]
     */
    public function findAllRoles(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM roles ORDER BY name ASC');
        $stmt->execute();

        return array_map(
            fn($data) => EntityMapper::toRole($data),
            $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []
        );
    }

    public function deactivate(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET active = 0 WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function update(int $id, string $name, string $email, int $roleId, bool $active): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET name = :name, email = :email, role_id = :role_id, active = :active WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role_id' => $roleId,
            'active' => $active ? 1 : 0,
        ]);
    }
}
