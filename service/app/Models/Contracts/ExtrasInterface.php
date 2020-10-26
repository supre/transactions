<?php


namespace App\Models\Contracts;


interface ExtrasInterface
{
    public function getPrice()
    : float;

    public function getPriceWithoutDecimals()
    : int;

    public function getType()
    : string;

    public function getId()
    : string;
}