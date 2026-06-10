<?php

namespace App\Models;

class Customer
{
    private int $id = 0;
    private string $name = '';
    private ?string $phone = null;
    private ?string $email = null;
    private ?string $notes = null;
    private string $createdAt = '';
    private string $updatedAt = '';
    /** @var Vehicle[] */
    private array $vehicles = [];

    public function __construct(
        int $id = 0,
        string $name = '',
        ?string $phone = null,
        ?string $email = null,
        ?string $notes = null,
        string $createdAt = '',
        string $updatedAt = '',
        array $vehicles = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->notes = $notes;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->vehicles = $vehicles;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
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

    /**
     * @return Vehicle[]
     */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }

    /**
     * @param Vehicle[] $vehicles
     */
    public function setVehicles(array $vehicles): self
    {
        $this->vehicles = $vehicles;
        return $this;
    }

    public function addVehicle(Vehicle $vehicle): self
    {
        $this->vehicles[] = $vehicle;
        return $this;
    }
}
