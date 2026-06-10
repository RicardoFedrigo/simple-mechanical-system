<?php

namespace App\Models;

/**
 * Entity mapper helper to convert database arrays to entity objects
 */
class EntityMapper
{
    public static function toRole(array $data): Role
    {
        return new Role(
            id: $data['id'] ?? 0,
            name: $data['name'] ?? ''
        );
    }

    public static function toUser(array $data, ?Role $role = null): User
    {
        // If role data is included, map it
        if ($role === null && !empty($data['role'])) {
            $role = self::toRole(['id' => $data['role_id'] ?? 0, 'name' => $data['role']]);
        }

        return new User(
            id: $data['id'] ?? 0,
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password_hash'] ?? $data['password'] ?? '',
            roleId: $data['role_id'] ?? 0,
            active: (bool)($data['active'] ?? true),
            createdAt: $data['created_at'] ?? '',
            updatedAt: $data['updated_at'] ?? '',
            role: $role
        );
    }

    public static function toCustomer(array $data, array $vehicles = []): Customer
    {
        return new Customer(
            id: $data['id'] ?? 0,
            name: $data['name'] ?? '',
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            notes: $data['notes'] ?? null,
            createdAt: $data['created_at'] ?? '',
            updatedAt: $data['updated_at'] ?? '',
            vehicles: $vehicles
        );
    }

    public static function toCarBrand(array $data): CarBrand
    {
        return new CarBrand(
            id: $data['id'] ?? 0,
            name: $data['name'] ?? ''
        );
    }

    public static function toVehicle(array $data, ?Customer $customer = null, ?CarBrand $carBrand = null): Vehicle
    {
        return new Vehicle(
            id: $data['id'] ?? 0,
            customerId: $data['customer_id'] ?? 0,
            carBrandId: $data['car_brand_id'] ?? null,
            plateNumber: $data['plate_number'] ?? '',
            model: $data['model'] ?? '',
            year: $data['year'] ?? null,
            status: $data['status'] ?? 'ENTERED',
            createdAt: $data['created_at'] ?? '',
            updatedAt: $data['updated_at'] ?? '',
            customer: $customer,
            carBrand: $carBrand
        );
    }

    public static function toMechanic(array $data, ?User $user = null): Mechanic
    {
        return new Mechanic(
            id: $data['id'] ?? 0,
            name: $data['name'] ?? $data['mechanic_name'] ?? '',
            specialty: $data['specialty'] ?? null,
            phone: $data['phone'] ?? null,
            createdAt: $data['created_at'] ?? '',
            updatedAt: $data['updated_at'] ?? '',
            userId: $data['user_id'] ?? $data['mechanic_user_id'] ?? 0,
            user: $user
        );
    }

    public static function toInventoryPart(array $data): InventoryPart
    {
        return new InventoryPart(
            id: $data['id'] ?? 0,
            name: $data['name'] ?? '',
            sku: $data['sku'] ?? '',
            quantity: (int)($data['quantity'] ?? 0),
            price: (float)($data['price'] ?? 0.00),
            createdAt: $data['created_at'] ?? '',
            updatedAt: $data['updated_at'] ?? ''
        );
    }

    public static function toServiceOrder(
        array $data,
        ?Customer $customer = null,
        ?Vehicle $vehicle = null,
        ?Mechanic $mechanic = null,
        array $items = []
    ): ServiceOrder {
        return new ServiceOrder(
            id: $data['id'] ?? 0,
            customerId: $data['customer_id'] ?? 0,
            vehicleId: $data['vehicle_id'] ?? null,
            mechanicId: $data['mechanic_id'] ?? null,
            status: $data['status'] ?? 'PENDING',
            subtotal: (float)($data['subtotal'] ?? 0.00),
            tax: (float)($data['tax'] ?? 0.00),
            total: (float)($data['total'] ?? 0.00),
            createdAt: $data['created_at'] ?? '',
            updatedAt: $data['updated_at'] ?? '',
            serviceDescription: $data['service_description'] ?? null,
            customer: $customer,
            vehicle: $vehicle,
            mechanic: $mechanic,
            items: $items
        );
    }

    public static function toServiceOrderItem(array $data, ?ServiceOrder $serviceOrder = null): ServiceOrderItem
    {
        return new ServiceOrderItem(
            id: $data['id'] ?? 0,
            serviceOrderId: $data['service_order_id'] ?? 0,
            quantity: (int)($data['quantity'] ?? 1),
            unitPrice: (float)($data['unit_price'] ?? 0.00),
            total: (float)($data['total'] ?? 0.00),
            description: $data['description'] ?? null,
            serviceOrder: $serviceOrder
        );
    }

    public static function toAuditInventoryPart(array $data, ?InventoryPart $part = null): AuditInventoryPart
    {
        return new AuditInventoryPart(
            id: $data['id'] ?? 0,
            partId: $data['part_id'] ?? 0,
            actionType: $data['action_type'] ?? '',
            quantity: (int)($data['quantity'] ?? 0),
            actual: (int)($data['actual'] ?? 0),
            changedBy: $data['changed_by'] ?? null,
            createdAt: $data['create_at'] ?? $data['created_at'] ?? '',
            part: $part
        );
    }
}
