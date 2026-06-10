<?php

namespace App\Models;

class Vehicle
{
    private int $id = 0;
    private int $customerId = 0;
    private ?int $carBrandId = null;
    private string $plateNumber = '';
    private string $model = '';
    private ?int $year = null;
    private string $status = 'ENTERED';
    private string $createdAt = '';
    private string $updatedAt = '';
    private ?Customer $customer = null;
    private ?CarBrand $carBrand = null;

    public function __construct(
        int $id = 0,
        int $customerId = 0,
        ?int $carBrandId = null,
        string $plateNumber = '',
        string $model = '',
        ?int $year = null,
        string $status = 'ENTERED',
        string $createdAt = '',
        string $updatedAt = '',
        ?Customer $customer = null,
        ?CarBrand $carBrand = null
    ) {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->carBrandId = $carBrandId;
        $this->plateNumber = $plateNumber;
        $this->model = $model;
        $this->year = $year;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->customer = $customer;
        $this->carBrand = $carBrand;
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

    public function getCarBrandId(): ?int
    {
        return $this->carBrandId;
    }

    public function setCarBrandId(?int $carBrandId): self
    {
        $this->carBrandId = $carBrandId;
        return $this;
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }

    public function setPlateNumber(string $plateNumber): self
    {
        $this->plateNumber = $plateNumber;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }

    public function getCarBrand(): ?CarBrand
    {
        return $this->carBrand;
    }

    public function setCarBrand(?CarBrand $carBrand): self
    {
        $this->carBrand = $carBrand;
        return $this;
    }
}
