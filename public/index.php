<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Helpers/helpers.php';

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
        $_SERVER[trim($key)] = trim($value);
    }
}

$router = new App\Core\Router();
require __DIR__ . '/../routes/api.php';
require __DIR__ . '/../routes/web.php';

$request = new App\Core\Request();
$response = $router->dispatch($request);

if ($response !== null) {
    echo $response;
}
