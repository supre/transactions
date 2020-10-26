<?php


namespace App\Models\Traits;


use App\Exceptions\ExtraNotAllowed;
use App\Exceptions\MaxExtrasExceeded;
use App\Models\Contracts\ExtrasInterface;

trait ExtrasApplicable
{
    abstract public function allowedExtraTypes()
    : array;

    public function getExtras()
    : array
    {
        return $this->attachedExtras;
    }

    public function maxExtras()
    : int
    {
        return $this->maxExtras;
    }

    public function hasExtras()
    : bool
    {
        return count($this->attachedExtras) > 0;
    }

    /**
     * @param ExtrasInterface $extra
     * @param int $quantity
     * @throws MaxExtrasExceeded
     * @throws ExtraNotAllowed
     */
    public function addExtra(ExtrasInterface $extra, int $quantity)
    : void {
        if (!in_array($extra->getType(), $this->allowedExtraTypes())) {
            throw new ExtraNotAllowed($extra->getType(), self::class);
        }

        $this->checkExtrasCanStillBeAdded($quantity);

        $this->attachedExtras[] = [
            'item'     => $extra,
            'quantity' => $quantity
        ];
    }

    private function getTotalPriceForAttachedExtrasInDecimals()
    : int
    {
        return collect($this->attachedExtras)->reduce(
            function (int $total, array $extra) {
                ['item' => $item, 'quantity' => $quantity] = $extra;
                return ($item->getPriceInDecimals() * $quantity) + $total;
            },
            0
        );
    }

    private function setMaxExtras(int $maxExtras)
    : void {
        $this->maxExtras = $maxExtras;
    }

    private function currentExtrasCount()
    : int
    {
        return collect($this->attachedExtras)->reduce(
            function (int $totalQuantity, array $extra) {
                return $extra['quantity'] + $totalQuantity;
            },
            0
        );
    }

    /**
     * @param int $quantity
     * @throws MaxExtrasExceeded
     */
    private function checkExtrasCanStillBeAdded(int $quantity)
    : void {
        // If maxExtras are less than 0, then we assume them to be infinite
        if ($this->maxExtras() < 0) {
            return;
        }

        if ($this->currentExtrasCount() + $quantity > $this->maxExtras() || $quantity > $this->maxExtras()) {
            throw new MaxExtrasExceeded($this->maxExtras);
        }
    }

    private int $maxExtras;
    private array $attachedExtras = [];
}