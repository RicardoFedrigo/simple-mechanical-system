<?php

namespace App\Models;

class AuditInventoryPart
{
    private int $id = 0;
    private int $partId = 0;
    private string $actionType = '';
    private int $quantity = 0;
    private int $actual = 0;
    private ?string $changedBy = null;
    private string $createdAt = '';
    private ?InventoryPart $part = null;

    public function __construct(
        int $id = 0,
        int $partId = 0,
        string $actionType = '',
        int $quantity = 0,
        int $actual = 0,
        ?string $changedBy = null,
        string $createdAt = '',
        ?InventoryPart $part = null
    ) {
        $this->id = $id;
        $this->partId = $partId;
        $this->actionType = $actionType;
        $this->quantity = $quantity;
        $this->actual = $actual;
        $this->changedBy = $changedBy;
        $this->createdAt = $createdAt;
        $this->part = $part;
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

    public function getPartId(): int
    {
        return $this->partId;
    }

    public function setPartId(int $partId): self
    {
        $this->partId = $partId;
        return $this;
    }

    public function getActionType(): string
    {
        return $this->actionType;
    }

    public function setActionType(string $actionType): self
    {
        $this->actionType = $actionType;
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

    public function getActual(): int
    {
        return $this->actual;
    }

    public function setActual(int $actual): self
    {
        $this->actual = $actual;
        return $this;
    }

    public function getChangedBy(): ?string
    {
        return $this->changedBy;
    }

    public function setChangedBy(?string $changedBy): self
    {
        $this->changedBy = $changedBy;
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

    public function getPart(): ?InventoryPart
    {
        return $this->part;
    }

    public function setPart(?InventoryPart $part): self
    {
        $this->part = $part;
        return $this;
    }
}
