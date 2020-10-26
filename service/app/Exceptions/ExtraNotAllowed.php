<?php


namespace App\Exceptions;


use Exception;
use Throwable;

class ExtraNotAllowed extends Exception implements Throwable
{
    public function __construct(string $type, string $class, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );

        $this->type = $type;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getType()
    : string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getClass()
    : string
    {
        return $this->class;
    }

    private string $type;
    private string $class;
}