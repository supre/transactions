<?php

namespace App\Models;

use InvalidArgumentException;

abstract class ElectronicItem
{
    public const ELECTRONIC_ITEM_TELEVISION = 'television';
    public const ELECTRONIC_ITEM_CONSOLE = 'console';
    public const ELECTRONIC_ITEM_MICROWAVE = 'microwave';
    public const ELECTRONIC_ITEM_CONTROLLER = 'controller';

    /**
     * ElectronicItem constructor.
     * @param string $id
     * @param float $price
     * @param string $type
     * @param string $name
     * @param bool $canBeSoldStandalone
     */
    public function __construct(string $id, float $price, string $type, string $name, bool $canBeSoldStandalone)
    {
        if (!in_array($type, self::$types)) {
            throw new InvalidArgumentException("{$type} is not a valid electronic item");
        }

        $this->type = $type;
        $this->price = $price;
        $this->name = $name;
        $this->canBeSoldStandalone = $canBeSoldStandalone;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    : string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function canBeSoldStandalone()
    : bool
    {
        return $this->canBeSoldStandalone;
    }

    public function getPrice()
    : float
    {
        return $this->price;
    }

    public function getPriceInDecimals()
    : int
    {
        return $this->price * 100;
    }

    public function getTotalPriceInDecimals()
    : int
    {
        return $this->getPriceInDecimals();
    }

    public function getType()
    : string
    {
        return $this->type;
    }

    public function is(string $type)
    {
        return $type === $this->getType();
    }

    private float $price;
    private string $type;
    private string $name;
    private bool $canBeSoldStandalone;
    private static $types = array(
        self::ELECTRONIC_ITEM_CONSOLE,
        self::ELECTRONIC_ITEM_MICROWAVE,
        self::ELECTRONIC_ITEM_TELEVISION,
        self::ELECTRONIC_ITEM_CONTROLLER
    );
    private string $id;
}