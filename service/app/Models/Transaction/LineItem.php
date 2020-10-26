<?php


namespace app\Models\Transaction;


use App\Models\ElectronicItem;

class LineItem
{
    public function __construct(ElectronicItem $item, int $quantity)
    {
        $this->item = $item;
        $this->quantity = $quantity;
    }

    /**
     * @return ElectronicItem
     */
    public function getItem()
    : ElectronicItem
    {
        return $this->item;
    }

    /**
     * @return int
     */
    public function getQuantity()
    : int
    {
        return $this->quantity;
    }

    public function getTotalPrice()
    : float
    {
        return $this->getTotalPriceWithoutDecimals() / 100;
    }

    public function getTotalPriceWithoutDecimals()
    : float
    {
        return $this->item->getTotalPriceWithoutDecimals() * $this->quantity;
    }

    /**
     * @var ElectronicItem
     */
    private ElectronicItem $item;
    private int $quantity;
}