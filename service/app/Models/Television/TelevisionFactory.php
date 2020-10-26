<?php


namespace App\Models\Television;


use App\Models\Contracts\ElectronicItemFactoryInterface;
use App\Models\ElectronicItem;

class TelevisionFactory implements ElectronicItemFactoryInterface
{
    public function __construct(int $maxExtras, bool $canBeSoldStandalone)
    {
        $this->maxExtras = $maxExtras;
        $this->canBeSoldStandalone = $canBeSoldStandalone;
    }

    public function createItemFromData(array $data)
    : Television {
        ['id' => $id, 'name' => $name, 'price' => $price] = $data;

        return new Television(
            $id, $this->maxExtras, $price, ElectronicItem::ELECTRONIC_ITEM_TELEVISION,
            $name, $this->canBeSoldStandalone
        );
    }

    private int $maxExtras;
    private bool $canBeSoldStandalone;
}