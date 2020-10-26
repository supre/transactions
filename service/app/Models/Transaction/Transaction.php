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

    public function getSortedLineItems(bool $desc = false)
    : array {
        $sortedLineitems = [];

        collect($this->lineItems)->each(
            function (LineItem $lineItem) use (&$sortedLineitems) {
                $sortedLineitems[$lineItem->getTotalPriceWithoutDecimals()] = $lineItem;
            }
        );

        return collect(($sortedLineitems))->sortKeys(SORT_NUMERIC, $desc)->toArray();
    }

    /**
     * @var LineItem[]
     */
    private array $lineItems;
}