<?php

namespace App\Services\Items;

use App\Repositories\ItemRepository;

class SearchItemService
{
    private ItemRepository $repository;

    public function __construct()
    {
        $this->repository = new ItemRepository();
    }

    public function execute(string $term): array
    {
        return $this->repository->search($term);
    }
}
