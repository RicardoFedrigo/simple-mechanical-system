<?php

namespace App\Repositories;

use App\Core\BaseModel;
use PDO;

class OrderCreateRepository extends BaseModel
{
    public function createCustomer(string $name, ?string $phone, ?string $email): int
    {
        $query = $this->db->prepare(
            'INSERT INTO customers (name, phone, email) VALUES (:name, :phone, :email)'
        );
        $query->execute([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function createVehicle(int $customerId, ?int $carBrandId, string $model, ?string $plateNumber = null, ?int $year = null): int
    {
        $query = $this->db->prepare(
            'INSERT INTO vehicles (customer_id, car_brand_id, plate_number, model, year, status) VALUES (:customer_id, :car_brand_id, :plate_number, :model, :year, :status)'
        );
        $query->execute([
            'customer_id' => $customerId,
            'car_brand_id' => $carBrandId,
            'plate_number' => $plateNumber,
            'model' => $model,
            'year' => $year,
            'status' => 'ENTERED',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function createServiceOrder(int $customerId, ?int $vehicleId, ?int $mechanicId, string $status, float $subtotal, float $tax, float $total): int
    {
        $query = $this->db->prepare(
            'INSERT INTO service_orders (customer_id, vehicle_id, mechanic_id, status, subtotal, tax, total) VALUES (:customer_id, :vehicle_id, :mechanic_id, :status, :subtotal, :tax, :total)'
        );
        $query->execute([
            'customer_id' => $customerId,
            'vehicle_id' => $vehicleId,
            'mechanic_id' => $mechanicId,
            'status' => $status,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function createServiceOrderItem(int $serviceOrderId, string $description, int $quantity, float $unitPrice, float $total): int
    {
        $query = $this->db->prepare(
            'INSERT INTO service_order_items (service_order_id, description, quantity, unit_price, total) VALUES (:service_order_id, :description, :quantity, :unit_price, :total)'
        );
        $query->execute([
            'service_order_id' => $serviceOrderId,
            'description' => $description,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $total,
        ]);

        return (int) $this->db->lastInsertId();
    }
}
