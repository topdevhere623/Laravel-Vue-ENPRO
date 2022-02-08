<?php


namespace App\Contracts\CIM\Wires;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface ConnectivityNodeInterface extends IdentifiedObjectInterface
{
    public function getTerminals() : array;
}
