<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Models\ServiceOrder;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderListRepository;
use App\Services\Orders\AddOrderItemService;
use App\Services\Items\SearchItemService;
use App\Services\Orders\CreateService;
use App\Services\Orders\GetOrderListService;
use App\Services\Orders\GetOrderByIdService;
use App\Services\Orders\UpdateOrderStatusService;
use App\Services\CarBrands\GetAllCarBrands;

class OrderController extends BaseController
{
    private GetAllCarBrands $getAllCarBrands;
    private CreateService $createOrderService;
    private GetOrderListService $getOrderListService;
    private UpdateOrderStatusService $updateOrderStatusService;
    private AddOrderItemService $addOrderItemService;
    private SearchItemService $searchItemService;
    private CustomerRepository $customerRepository;
    private OrderListRepository $orderListRepository;
    private GetOrderByIdService $getOrderByIdService;

    public function __construct()
    {
        $this->getAllCarBrands = new GetAllCarBrands();
        $this->createOrderService = new CreateService();
        $this->getOrderListService = new GetOrderListService();
        $this->updateOrderStatusService = new UpdateOrderStatusService();
        $this->addOrderItemService = new AddOrderItemService();
        $this->searchItemService = new SearchItemService();
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
        
        $page = (int)($query['page'] ?? 1);
        $limit = 20;

        $user = $_SESSION['user'] ?? [];
        if (($user['role'] ?? '') === 'Mechanic' && !empty($user['id'])) {
            $filters['mechanic_user_id'] = $user['id'];
        }

        $orders = $this->getOrderListService->execute(array_filter($filters), $page, $limit);
        $totalOrders = $this->getOrderListService->count(array_filter($filters));
        $totalPages = ceil($totalOrders / $limit);

        return $this->view('orders/index', [
            'title' => 'Service Orders',
            'tickets' => $orders,
            'currentRole' => $user['role'] ?? null,
            'page' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters,
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
        error_log("Order Details for ID " . $id . ": " . print_r($order, true));
        if (!$order) {
            http_response_code(404);
            return $this->view('404', ['message' => 'Order not found']);
        }

        $user = $_SESSION['user'] ?? [];
        if (($user['role'] ?? '') === 'Mechanic' && ($user['role'] ?? '') !== 'Admin') {
            if (($order->getMechanic()?->getUserId() ?? 0) !== ($user['id'] ?? 0)) {
                http_response_code(403);
                return $this->view('404', ['message' => 'Order not found']);
            }
        }

        $items = $this->getOrderByIdService->items($id);

        return $this->view('orders/details', [
            'title' => 'Order #' . $order->getId(),
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
        $order = new ServiceOrder(
            status: 'PENDING',
            subtotal: 0.0,
            tax: 0.0,
            total: 0.0,
            serviceDescription: trim((string) $request->input('description', '')) ?: null
        );

        $form = [
            'customer_id' => (int) $request->input('customer_id', 0),
            'customer_name' => trim((string) $request->input('customer_name', '')),
            'customer_phone' => trim((string) $request->input('customer_phone', '')),
            'customer_email' => trim((string) $request->input('customer_email', '')),
            'vehicle_brand_id' => $request->input('vehicle_brand_id', ''),
            'vehicle_model' => trim((string) $request->input('vehicle_model', '')),
            'vehicle_plate' => trim((string) $request->input('vehicle_plate', '')),
            'vehicle_year' => $request->input('vehicle_year', ''),
        ];

        try {
            $this->createOrderService->execute($order, $form);
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
        $status = (string) $request->input('status', '');

        try {
            $this->updateOrderStatusService->execute($orderId, $status);
            flash('success', 'Order status updated successfully.');
        } catch (\Throwable $e) {
            flash('error', 'Failed to update order status: ' . $e->getMessage());
        }

        $this->redirect('/orders/' . $orderId);
    }

    public function addOrderItem(Request $request): void
    {
        $orderId = (int) $request->input('order_id', 0);
        $itemId = (int) $request->input('item_id', 0);
        $quantity = (int) $request->input('quantity', 1);
        $unitPrice = (float) $request->input('unit_price', 0.0);

        $user = $_SESSION['user'] ?? [];
        $userId = $user['id'] ?? 0;
        $role = $user['role'] ?? '';

        if ($role !== 'Mechanic' && $role !== 'Admin') {
            http_response_code(403);
            flash('error', 'Only mechanics or admins can add items.');
            $this->redirect('/orders/' . $orderId);
        }

        try {
            $this->addOrderItemService->execute($orderId, $userId, $itemId, $quantity, $unitPrice);
            flash('success', 'Item added successfully.');
        } catch (\Throwable $e) {
            flash('error', 'Failed to add item: ' . $e->getMessage());
        }

        $this->redirect('/orders/' . $orderId);
    }

    public function searchItems(Request $request): string
    {
        $term = trim((string) $request->input('term', ''));
        $items = $this->searchItemService->execute($term);

        return json_encode(array_map(fn($item) => [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'sku' => $item->getSku(),
            'unit_price' => $item->getUnitPrice()
        ], $items));
    }
}
