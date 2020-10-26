<?php


namespace App\Models\Controller;


use App\Models\Contracts\ElectronicItemFactoryInterface;

class ControllerFactory implements ElectronicItemFactoryInterface
{
    public function __construct(bool $canBeSoldStandAlone)
    {
        $this->canBeSoldStandAlone = $canBeSoldStandAlone;
    }

    public function createItemFromData(array $data)
    : Controller {
        ['id' => $id, 'name' => $name, 'price' => $price, 'isWireless' => $isWireless] = $data;

        return new Controller($id, $price, $isWireless, $name, $this->canBeSoldStandAlone);
    }

    private bool $canBeSoldStandAlone;
}