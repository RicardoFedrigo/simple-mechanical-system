<?php

return [
    'dsn' => sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', env('DB_HOST', 'mysql'), (int) env('DB_PORT', 3306), env('DB_DATABASE', 'workshop')),
    'username' => env('DB_USERNAME', 'workshop'),
    'password' => env('DB_PASSWORD', 'workshop'),
];
