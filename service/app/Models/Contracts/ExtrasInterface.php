<?php


namespace App\Models\Contracts;


interface ExtrasInterface
{
    public function getPrice()
    : float;

    public function getPriceInDecimals()
    : int;

    public function getType()
    : string;
}