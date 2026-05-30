<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;
use App\Services\CarBrands\GetAllCarBrands;

class OrderController extends BaseController
{
    private GetAllCarBrands $getAllCarBrands;


    public function __construct()
    {

        $this->getAllCarBrands = new GetAllCarBrands();
    }

    public function index(Request $request): string
    {
        $orders = $this->getOrders();

        return $this->view('orders/index', [
            'title' => 'Service Orders',
            'tickets' => $orders,
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

        $order = $this->getOrderById($id);
        if (!$order) {
            http_response_code(404);
            return $this->view('404', ['message' => 'Order not found']);
        }

        $items = $this->getOrderItems($id);

        return $this->view('orders/details', [
            'title' => 'Order #' . $order['id'],
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function create(Request $request): string
    {
        $customers = $this->getCustomers();
        $vehicle_brands = $this->getAllCarBrands->execute();

        return $this->view('orders/create', [
            'title' => 'Create Service Order',
            'customers' => $customers,
            'vehicle_brands' => $vehicle_brands
        ]);
    }

    public function store(Request $request): void
    {
        $customer_id = (int) $request->input('customer_id', 0);
        $vehicle_id = $request->input('vehicle_id') ? (int) $request->input('vehicle_id') : null;
        $mechanic_id = $request->input('mechanic_id') ? (int) $request->input('mechanic_id') : null;
        $status = trim((string) $request->input('status', 'PENDING'));
        $subtotal = (float) $request->input('subtotal', 0);
        $tax = (float) $request->input('tax', 0);
        $total = $subtotal + $tax;

        if ($customer_id <= 0) {
            flash('error', 'Customer is required.');
            $this->redirect('/orders/create');
        }

        try {


            flash('success', 'Service order created successfully.');
            $this->redirect('/orders');
        } catch (\Exception $e) {
            flash('error', 'Failed to create order: ' . $e->getMessage());
            $this->redirect('/orders/create');
        }
    }

    private function getOrders(): array
    {
        return [];
    }

    private function getOrderById(int $id): ?array
    {
        // $stmt = $this->db->prepare(
        //     'SELECT 
        //         so.*,
        //         c.name as customer_name,
        //         c.phone as customer_phone,
        //         c.email as customer_email,
        //         v.plate_number,
        //         v.model as vehicle_model,
        //         v.year as vehicle_year,
        //         m.name as mechanic_name
        //      FROM service_orders so 
        //      LEFT JOIN customers c ON so.customer_id = c.id 
        //      LEFT JOIN vehicles v ON so.vehicle_id = v.id 
        //      LEFT JOIN mechanics m ON so.mechanic_id = m.id 
        //      WHERE so.id = :id'
        // );
        // $stmt->execute(['id' => $id]);

        // return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return null;
    }

    private function getOrderItems(int $id): array
    {
        // $stmt = $this->db->prepare(
        //     'SELECT * FROM service_order_items WHERE service_order_id = :id ORDER BY id ASC'
        // );
        // $stmt->execute(['id' => $id]);

        // return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return [];
    }

    private function getCustomers(): array
    {
        // $stmt = $this->db->query('SELECT id, name FROM customers ORDER BY name ASC');
        // return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return [];
    }

    private function getMechanics(): array
    {
        // $stmt = $this->db->query('SELECT id, name FROM mechanics ORDER BY name ASC');
        // return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        return [];
    }
}
