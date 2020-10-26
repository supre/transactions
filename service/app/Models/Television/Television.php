<?php


namespace App\Models\Television;

use App\Models\Contracts\ExtrasAddableInterface;
use App\Models\ElectronicItem;
use App\Models\Traits\ExtrasApplicable;


class Television extends ElectronicItem implements ExtrasAddableInterface
{
    use ExtrasApplicable;

    public function __construct(
        string $id,
        int $maxExtras,
        float $price,
        string $type,
        string $name,
        bool $canBeSoldStandalone
    ) {
        parent::__construct($id, $price, $type, $name, $canBeSoldStandalone);
        $this->setMaxExtras($maxExtras);
    }

    public function allowedExtraTypes()
    : array
    {
        return [
            self::ELECTRONIC_ITEM_CONTROLLER
        ];
    }

    public function getTotalPriceInDecimals()
    : int
    {
        return $this->getPriceInDecimals() + $this->getTotalPriceForAttachedExtrasInDecimals();
    }
}