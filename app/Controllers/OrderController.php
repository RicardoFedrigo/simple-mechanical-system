<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderListRepository;
use App\Services\CarBrands\GetAllCarBrands;
use App\Services\Orders\CreateService;
use App\Services\Orders\GetOrderListService;
use App\Services\Orders\GetOrderByIdService;
use App\Services\Orders\UpdateOrderStatusService;

class OrderController extends BaseController
{
    private GetAllCarBrands $getAllCarBrands;
    private CreateService $createOrderService;
    private GetOrderListService $getOrderListService;
    private UpdateOrderStatusService $updateOrderStatusService;
    private CustomerRepository $customerRepository;
    private OrderListRepository $orderListRepository;
    private GetOrderByIdService $getOrderByIdService;

    public function __construct()
    {
        $this->getAllCarBrands = new GetAllCarBrands();
        $this->createOrderService = new CreateService();
        $this->getOrderListService = new GetOrderListService();
        $this->updateOrderStatusService = new UpdateOrderStatusService();
        $this->getOrderByIdService = new GetOrderByIdService();
        $this->customerRepository = new CustomerRepository();
        $this->orderListRepository = new OrderListRepository();
    }

    public function index(Request $request): string
    {
        $query = $request->query();
        $filters = [
            'term' => trim($query['term'] ?? ''),
            'status' => trim($query['status'] ?? ''),
        ];

        $user = $_SESSION['user'] ?? [];
        if (($user['role'] ?? '') === 'Mechanic' && !empty($user['id'])) {
            $filters['mechanic_user_id'] = $user['id'];
        }

        $orders = $this->getOrderListService->execute(array_filter($filters));

        return $this->view('orders/index', [
            'title' => 'Service Orders',
            'tickets' => $orders,
            'currentRole' => $user['role'] ?? null,
        ]);
    }

    public function show(Request $request): string
    {
        $uri = $request->uri();
        preg_match('#/orders/(\d+)#', $uri, $matches);
        $id = (int) ($matches[1] ?? 0);

        if ($id <= 0) {
            $this->redirect('/orders');
        }

        $order = $this->getOrderByIdService->execute($id);
        if (!$order) {
            http_response_code(404);
            return $this->view('404', ['message' => 'Order not found']);
        }

        $user = $_SESSION['user'] ?? [];
        if (($user['role'] ?? '') === 'Mechanic' && $order['mechanic_user_id'] !== ($user['id'] ?? 0)) {
            http_response_code(403);
            return $this->view('404', ['message' => 'Order not found']);
        }

        $items = $this->getOrderByIdService->items($id);

        return $this->view('orders/details', [
            'title' => 'Order #' . $order['id'],
            'order' => $order,
            'items' => $items,
            'currentRole' => $user['role'] ?? null,
            'currentUserId' => $user['id'] ?? null,
        ]);
    }

    public function create(Request $request): string
    {
        $customers = $this->customerRepository->findAll();
        $vehicle_brands = $this->getAllCarBrands->execute();

        return $this->view('orders/createOrder', [
            'title' => 'Create Service Order',
            'customers' => $customers,
            'vehicle_brands' => $vehicle_brands,
        ]);
    }

    public function store(Request $request): void
    {
        $form = [
            'customer_id' => (int) $request->input('customer_id', 0),
            'customer_name' => trim((string) $request->input('customer_name', '')),
            'customer_phone' => trim((string) $request->input('customer_phone', '')),
            'customer_email' => trim((string) $request->input('customer_email', '')),
            'vehicle_brand_id' => $request->input('vehicle_brand_id', ''),
            'vehicle_model' => trim((string) $request->input('vehicle_model', '')),
            'vehicle_plate' => trim((string) $request->input('vehicle_plate', '')),
            'vehicle_year' => $request->input('vehicle_year', ''),
            'description' => trim((string) $request->input('description', '')),
            'status' => 'PENDING',
            'subtotal' => 0.0,
            'tax' => 0.0,
            'total' => 0.0,
        ];

        try {
            $this->createOrderService->execute($form);
            flash('success', 'Service order created successfully.');
            $this->redirect('/orders');
        } catch (\Throwable $e) {
            flash('error', 'Failed to create order: ' . $e->getMessage());
            $this->redirect('/orders/create');
        }
    }

    public function updateStatus(Request $request): void
    {
        $orderId = (int) $request->input('order_id', 0);
        $status = trim((string) $request->input('status', ''));
        $user = $_SESSION['user'] ?? [];
        $userId = $user['id'] ?? 0;

        if ($orderId <= 0 || $status === '') {
            flash('error', 'Invalid order status update.');
            $this->redirect('/orders');
        }

        $order = $this->getOrderByIdService->execute($orderId);
        if (!$order) {
            flash('error', 'Order not found.');
            $this->redirect('/orders');
        }

        if (($user['role'] ?? '') === 'Mechanic' && $order['mechanic_user_id'] !== $userId) {
            http_response_code(403);
            flash('error', 'You are not authorized to update this order.');
            $this->redirect('/orders');
        }

        try {
            $this->updateOrderStatusService->execute($orderId, $status);
            flash('success', 'Order status updated successfully.');
        } catch (\Throwable $e) {
            flash('error', 'Failed to update order status: ' . $e->getMessage());
        }

        $this->redirect('/orders/' . $orderId);
    }
}
