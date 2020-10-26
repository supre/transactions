<?php


namespace App\Models\Controller;


use App\Models\Contracts\ExtrasInterface;
use App\Models\Contracts\WirelessInterface;
use App\Models\ElectronicItem;
use App\Models\Traits\WirelessApplicable;

class Controller extends ElectronicItem implements ExtrasInterface, WirelessInterface
{
    use WirelessApplicable;

    public function __construct(string $id, float $price, bool $isWireless, string $name, bool $canBeSoldStandalone)
    {
        parent::__construct($id, $price, self::ELECTRONIC_ITEM_CONTROLLER, $name, $canBeSoldStandalone);

        $isWireless ? $this->makeWireless() : null;
    }
}