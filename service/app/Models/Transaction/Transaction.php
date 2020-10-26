<?php


namespace App\Models\Transaction;


class Transaction
{
    public function __construct()
    {
        $this->lineItems = [];
    }

    public function addLineItem(LineItem $lineItem)
    : void {
        $this->lineItems[] = $lineItem;
    }

    /**
     * @return LineItem[]
     */
    public function getLineItems()
    : array
    {
        return $this->lineItems;
    }

    public function getTotalPrice()
    : float
    {
        return collect($this->lineItems)->reduce(
            function (float $total, LineItem $lineItem) {
                return $total += $lineItem->getTotalPrice();
            },
            0
        );
    }

    /**
     * @var LineItem[]
     */
    private array $lineItems;
}