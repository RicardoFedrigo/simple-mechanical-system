<?php

namespace App\Models;

class ServiceOrderItem
{
    private int $id = 0;
    private int $serviceOrderId = 0;
    private int $quantity = 1;
    private float $unitPrice = 0.00;
    private float $total = 0.00;
    private ?string $description = null;
    private ?ServiceOrder $serviceOrder = null;

    public function __construct(
        int $id = 0,
        int $serviceOrderId = 0,
        int $quantity = 1,
        float $unitPrice = 0.00,
        float $total = 0.00,
        ?string $description = null,
        ?ServiceOrder $serviceOrder = null
    ) {
        $this->id = $id;
        $this->serviceOrderId = $serviceOrderId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->total = $total;
        $this->description = $description;
        $this->serviceOrder = $serviceOrder;
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

    public function getServiceOrderId(): int
    {
        return $this->serviceOrderId;
    }

    public function setServiceOrderId(int $serviceOrderId): self
    {
        $this->serviceOrderId = $serviceOrderId;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getServiceOrder(): ?ServiceOrder
    {
        return $this->serviceOrder;
    }

    public function setServiceOrder(?ServiceOrder $serviceOrder): self
    {
        $this->serviceOrder = $serviceOrder;
        return $this;
    }
}
