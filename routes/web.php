<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;

$router->get('/', [new HomeController(), 'dashboard'], [AuthMiddleware::class]);
$router->get('/dashboard', [new HomeController(), 'dashboard'], [AuthMiddleware::class]);
$router->get('/customers', [new HomeController(), 'customers'], [AuthMiddleware::class]);
$router->get('/reports', [new HomeController(), 'reports'], [AuthMiddleware::class]);

$router->get('/login', [new AuthController(new App\Services\AuthService(new App\Repositories\UserRepository())), 'loginForm'], [GuestMiddleware::class]);
$router->post('/login', [new AuthController(new App\Services\AuthService(new App\Repositories\UserRepository())), 'login'], [GuestMiddleware::class]);
$router->post('/logout', [new AuthController(new App\Services\AuthService(new App\Repositories\UserRepository())), 'logout'], [AuthMiddleware::class]);

$router->get('/admin/dashboard', [new AdminController(), 'dashboard'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/users', [new AdminController(), 'users'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/settings', [new AdminController(), 'settings'], [AuthMiddleware::class, AdminMiddleware::class]);
