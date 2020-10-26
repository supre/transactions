<?php


namespace App\Http\Controllers;


trait SelfCallableTrait
{
    protected function call($methodName)
    : string {
        return get_class($this) . '@' . $methodName;
    }
}