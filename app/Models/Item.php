<?php

namespace App\Models;

class Item
{
    public function __construct(
        private int $id = 0,
        private string $name = '',
        private string $sku = '',
        private int $quantity = 0,
        private float $unitPrice = 0.00
    ) {}

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getSku(): string { return $this->sku; }
    public function getQuantity(): int { return $this->quantity; }
    public function getUnitPrice(): float { return $this->unitPrice; }
}
