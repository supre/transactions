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
        return ($this->item->getTotalPriceInDecimals() * $this->quantity) / 100;
    }

    /**
     * @var ElectronicItem
     */
    private ElectronicItem $item;
    private int $quantity;
}