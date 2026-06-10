<?php

namespace App\Models;

class InventoryPart
{
    private int $id = 0;
    private string $name = '';
    private string $sku = '';
    private int $quantity = 0;
    private float $price = 0.00;
    private string $createdAt = '';
    private string $updatedAt = '';

    public function __construct(
        int $id = 0,
        string $name = '',
        string $sku = '',
        int $quantity = 0,
        float $price = 0.00,
        string $createdAt = '',
        string $updatedAt = ''
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
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
}
