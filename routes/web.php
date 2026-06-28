<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CustomerController;
use App\Controllers\HomeController;
use App\Controllers\OrderController;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Services\AuthService;
use App\Repositories\UserRepository;

$authController = new AuthController(new AuthService(new UserRepository()));

$homeController = new HomeController();
$customerController = new CustomerController();

$router->get('/', [$homeController, 'dashboard'], [AuthMiddleware::class]);
$router->get('/dashboard', [$homeController, 'dashboard'], [AuthMiddleware::class]);
$router->get('/customers', [$homeController, 'customers'], [AuthMiddleware::class]);
$router->get('/customers/*/orders', [$customerController, 'orders'], [AuthMiddleware::class]);
$router->get('/customers/*', [$customerController, 'show'], [AuthMiddleware::class]);
$router->get('/reports', [$homeController, 'reports'], [AuthMiddleware::class]);

$orderController = new OrderController();

$router->get('/orders', [$orderController, 'index'], [AuthMiddleware::class]);
$router->get('/orders/create', [$orderController, 'create'], [AuthMiddleware::class]);
$router->post('/orders', [$orderController, 'store'], [AuthMiddleware::class]);
$router->post('/orders/status', [$orderController, 'updateStatus'], [AuthMiddleware::class]);
$router->post('/orders/add-item', [$orderController, 'addOrderItem'], [AuthMiddleware::class]);
$router->get('/orders/search-items', [$orderController, 'searchItems'], [AuthMiddleware::class]);
$router->get('/orders/*', [$orderController, 'show'], [AuthMiddleware::class]);

$router->get('/login', [$authController, 'loginForm'], [GuestMiddleware::class]);
$router->post('/login', [$authController, 'login'], [GuestMiddleware::class]);
$router->post('/logout', [$authController, 'logout'], [AuthMiddleware::class]);

$adminController = new AdminController();

$router->get('/admin/users', [$adminController, 'users'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/users/*/edit', [$adminController, 'editUser'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/users/*/edit', [$adminController, 'updateUser'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/users/*/deactivate', [$adminController, 'deactivateUser'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->post('/admin/users/*/activate', [$adminController, 'activateUser'], [AuthMiddleware::class, AdminMiddleware::class]);
$router->get('/admin/users/*', [$adminController, 'userDetails'], [AuthMiddleware::class, AdminMiddleware::class]);

