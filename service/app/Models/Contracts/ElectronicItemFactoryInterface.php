<?php


namespace App\Models\Contracts;


use App\Models\ElectronicItem;

interface ElectronicItemFactoryInterface
{
    /**
     * @param array $data
     * @return ElectronicItem
     */
    public function createItemFromData(array $data)
    : ElectronicItem;
}