<?php

namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\ServiceOrder;
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

    public function createServiceOrder(int $customerId, ?int $vehicleId, ?int $mechanicId, string $status, float $subtotal, float $tax, float $total, ?string $serviceDescription = null): int
    {
        $query = $this->db->prepare(
            'INSERT INTO service_orders (customer_id, vehicle_id, mechanic_id, status, subtotal, tax, total, service_description) VALUES (:customer_id, :vehicle_id, :mechanic_id, :status, :subtotal, :tax, :total, :service_description)'
        );
        $query->execute([
            'customer_id' => $customerId,
            'vehicle_id' => $vehicleId,
            'mechanic_id' => $mechanicId,
            'status' => $status,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'service_description' => $serviceDescription,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function create(ServiceOrder $order): int
    {
        $query = $this->db->prepare(
            'INSERT INTO service_orders (customer_id, vehicle_id, mechanic_id, status, subtotal, tax, total, service_description) VALUES (:customer_id, :vehicle_id, :mechanic_id, :status, :subtotal, :tax, :total, :service_description)'
        );
        $query->execute([
            'customer_id' => $order->getCustomerId(),
            'vehicle_id' => $order->getVehicleId(),
            'mechanic_id' => $order->getMechanicId(),
            'status' => $order->getStatus(),
            'subtotal' => $order->getSubtotal(),
            'tax' => $order->getTax(),
            'total' => $order->getTotal(),
            'service_description' => $order->getServiceDescription(),
        ]);

        return (int) $this->db->lastInsertId();
    }

    // Service order items insertion handled by dedicated flows when needed.
}
