<?php


namespace App\Exceptions;


use Exception;
use Throwable;

class MaxExtrasExceeded extends Exception implements Throwable
{
    public function __construct(int $maxExtras, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );
        $this->maxExtras = $maxExtras;
    }

    public function getMaxExtras()
    : int
    {
        return $this->maxExtras;
    }

    private int $maxExtras;

}