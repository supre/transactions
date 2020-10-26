<?php


namespace App\Models\Microwave;


use App\Models\ElectronicItem;

class Microwave extends ElectronicItem
{
    public function __construct(string $id, float $price, string $name, bool $canBeSoldStandalone)
    {
        parent::__construct(
            $id,
            $price,
            self::ELECTRONIC_ITEM_MICROWAVE,
            $name,
            $canBeSoldStandalone
        );
    }
}