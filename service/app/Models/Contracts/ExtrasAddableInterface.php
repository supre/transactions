<?php


namespace App\Models\Contracts;


use App\Exceptions\ExtraNotAllowed;
use App\Exceptions\MaxExtrasExceeded;

interface ExtrasAddableInterface
{
    public function maxExtras()
    : int;

    public function getExtras()
    : array;

    public function allowedExtraTypes()
    : array;

    public function hasExtras()
    : bool;

    /**
     * @param ExtrasInterface $extra
     * @param int $quantity
     * @throws MaxExtrasExceeded
     * @throws ExtraNotAllowed
     */
    public function addExtra(ExtrasInterface $extra, int $quantity)
    : void;
}