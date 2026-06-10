<?php

namespace App\Models;

class ServiceOrder
{
    private int $id = 0;
    private int $customerId = 0;
    private ?int $vehicleId = null;
    private ?int $mechanicId = null;
    private string $status = 'PENDING';
    private float $subtotal = 0.00;
    private float $tax = 0.00;
    private float $total = 0.00;
    private string $createdAt = '';
    private string $updatedAt = '';
    private ?string $serviceDescription = null;
    private ?Customer $customer = null;
    private ?Vehicle $vehicle = null;
    private ?Mechanic $mechanic = null;
    /** @var ServiceOrderItem[] */
    private array $items = [];

    public function __construct(
        int $id = 0,
        int $customerId = 0,
        ?int $vehicleId = null,
        ?int $mechanicId = null,
        string $status = 'PENDING',
        float $subtotal = 0.00,
        float $tax = 0.00,
        float $total = 0.00,
        string $createdAt = '',
        string $updatedAt = '',
        ?string $serviceDescription = null,
        ?Customer $customer = null,
        ?Vehicle $vehicle = null,
        ?Mechanic $mechanic = null,
        array $items = []
    ) {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->vehicleId = $vehicleId;
        $this->mechanicId = $mechanicId;
        $this->status = $status;
        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->total = $total;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->serviceDescription = $serviceDescription;
        $this->customer = $customer;
        $this->vehicle = $vehicle;
        $this->mechanic = $mechanic;
        $this->items = $items;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getVehicleId(): ?int
    {
        return $this->vehicleId;
    }

    public function setVehicleId(?int $vehicleId): self
    {
        $this->vehicleId = $vehicleId;
        return $this;
    }

    public function getMechanicId(): ?int
    {
        return $this->mechanicId;
    }

    public function setMechanicId(?int $mechanicId): self
    {
        $this->mechanicId = $mechanicId;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function setTax(float $tax): self
    {
        $this->tax = $tax;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getServiceDescription(): ?string
    {
        return $this->serviceDescription;
    }

    public function setServiceDescription(?string $serviceDescription): self
    {
        $this->serviceDescription = $serviceDescription;
        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    public function getMechanic(): ?Mechanic
    {
        return $this->mechanic;
    }

    public function setMechanic(?Mechanic $mechanic): self
    {
        $this->mechanic = $mechanic;
        return $this;
    }

    /**
     * @return ServiceOrderItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param ServiceOrderItem[] $items
     */
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function addItem(ServiceOrderItem $item): self
    {
        $this->items[] = $item;
        return $this;
    }
}
