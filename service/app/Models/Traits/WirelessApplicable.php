<?php

namespace App\Models\Traits;

trait WirelessApplicable
{
    public function isWireless()
    : bool
    {
        return $this->wireless;
    }

    private function makeWireless()
    : void
    {
        $this->wireless = true;
    }

    private bool $wireless = false;
}