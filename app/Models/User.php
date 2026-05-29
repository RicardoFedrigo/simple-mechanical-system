<?php

namespace App\Models;

class User
{
    public function __construct(
        public int $id = 0,
        public string $name = '',
        public string $email = '',
        public string $password = '',
        public string $role = 'Attendant'
    ) {
    }
}
