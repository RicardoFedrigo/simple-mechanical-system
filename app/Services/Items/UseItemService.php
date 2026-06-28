<?php

namespace App\Services\Items;

use App\Repositories\ItemRepository;
use Exception;

class UseItemService
{
    private ItemRepository $repository;

    public function __construct()
    {
        $this->repository = new ItemRepository();
    }

    

    public function execute(int $itemId, int $quantity): void
    {
        $item = $this->repository->findById($itemId);
        if (!$item) {
            throw new Exception("Item not found.");
        }

        if ($item->getQuantity() < $quantity) {
            throw new Exception("Insufficient quantity in stock.");
        }

        $newQuantity = $item->getQuantity() - $quantity;
        if (!$this->repository->updateQuantity($itemId, $newQuantity)) {
            throw new Exception("Failed to update item stock.");
        }      
    }
}
