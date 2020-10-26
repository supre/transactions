<?php


namespace App\Models\Microwave;


use App\Models\Contracts\ElectronicItemFactoryInterface;
use App\Models\ElectronicItem;

class MicrowaveFactory implements ElectronicItemFactoryInterface
{
    public function __construct(bool $canBeSoldStandAlone)
    {
        $this->canBeSoldStandAlone = $canBeSoldStandAlone;
    }

    public function createItemFromData(array $data)
    : ElectronicItem {
        ['id' => $id, 'name' => $name, 'price' => $price] = $data;

        return new Microwave($id, $price, $name, $this->canBeSoldStandAlone);
    }

    private bool $canBeSoldStandAlone;
}