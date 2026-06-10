<?php

namespace App\Models;

class Mechanic
{
    private int $id = 0;
    private string $name = '';
    private ?string $specialty = null;
    private ?string $phone = null;
    private string $createdAt = '';
    private string $updatedAt = '';
    private int $userId = 0;
    private ?User $user = null;

    public function __construct(
        int $id = 0,
        string $name = '',
        ?string $specialty = null,
        ?string $phone = null,
        string $createdAt = '',
        string $updatedAt = '',
        int $userId = 0,
        ?User $user = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->specialty = $specialty;
        $this->phone = $phone;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->userId = $userId;
        $this->user = $user;
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

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(?string $specialty): self
    {
        $this->specialty = $specialty;
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

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
