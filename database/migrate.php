#!/usr/bin/env php
<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This script must be run from the command line.\n");
    exit(1);
}

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
require $root . '/app/Helpers/helpers.php';

loadEnv($root . '/.env');
loadEnv($root . '/.env.example');

try {
    $pdo = App\Core\Database::getConnection();
} catch (Throwable $e) {
    fwrite(STDERR, "Database connection failed: " . $e->getMessage() . "\n");
    exit(1);
}

$files = [
    $root . '/database/migrations/001_schema.sql',
    $root . '/database/seeds/seed_roles.sql',
    $root . '/database/seeds/seed_users.sql',
    $root . '/database/seeds/seed_mechanic.sql',
];

foreach ($files as $file) {
    echo "Applying: {$file}\n";
    executeSqlFile($pdo, $file);
}

echo "Migrations and seed data applied successfully.\n";

function loadEnv(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2) + [1 => ''];
        $key = trim($key);
        $value = trim($value);

        if ($key === '') {
            continue;
        }

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

function executeSqlFile(PDO $pdo, string $file): void
{
    if (!is_file($file)) {
        throw new RuntimeException("SQL file not found: {$file}");
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("Failed to read SQL file: {$file}");
    }

    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $statement) {
        if ($statement === '') {
            continue;
        }

        $pdo->exec($statement);
    }
}
