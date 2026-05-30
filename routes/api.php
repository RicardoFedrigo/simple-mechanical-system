<?php

use App\Controllers\CustomerController;
use App\Middlewares\AuthMiddleware;

$router->get('/api/customer/search', [new CustomerController(), 'search'], [AuthMiddleware::class]);
