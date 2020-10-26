<?php


namespace App\Models\Console;

use App\Models\Contracts\ElectronicItemFactoryInterface;


class ConsoleFactory implements ElectronicItemFactoryInterface
{
    public function __construct(int $maxExtras, bool $canBeSoldStandalone)
    {
        $this->maxExtras = $maxExtras;
        $this->canBeSoldStandalone = $canBeSoldStandalone;
    }

    public function createItemFromData(array $data)
    : Console {
        ['id' => $id, 'name' => $name, 'price' => $price] = $data;

        return new Console($id, $price, $this->maxExtras, $name, $this->canBeSoldStandalone);
    }

    private int $maxExtras;
    private bool $canBeSoldStandalone;
}