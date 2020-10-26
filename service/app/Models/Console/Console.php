<?php


namespace App\Models\Console;


use App\Models\Contracts\ExtrasAddableInterface;
use App\Models\ElectronicItem;
use App\Models\Traits\ExtrasApplicable;

class Console extends ElectronicItem implements ExtrasAddableInterface
{

    use ExtrasApplicable;

    public function __construct(string $id, float $price, int $maxExtras, string $name, bool $canBeSoldStandalone)
    {
        parent::__construct($id, $price, self::ELECTRONIC_ITEM_CONSOLE, $name, $canBeSoldStandalone);
        $this->setMaxExtras($maxExtras);
    }

    public function allowedExtraTypes()
    : array
    {
        return [
            self::ELECTRONIC_ITEM_CONTROLLER
        ];
    }

    public function getTotalPriceWithoutDecimals()
    : int
    {
        return $this->getPriceInDecimals() + $this->getTotalPriceForAttachedExtrasInDecimals();
    }
}