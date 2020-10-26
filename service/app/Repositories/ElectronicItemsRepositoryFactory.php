<?php


namespace App\Repositories;


class ElectronicItemsRepositoryFactory
{
    public function createRepository(array $items)
    : ElectronicItemsRepository {
        return new ElectronicItemsRepository($items);
    }
}