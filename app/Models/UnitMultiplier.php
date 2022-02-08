<?php

namespace App\Models;

use App\Contracts\CIM\Wires\EnumerationInterface;
use App\Traits\EnumerationTrait;
use Illuminate\Database\Eloquent\Model;

class UnitMultiplier extends Model implements EnumerationInterface
{
    public $timestamps = false;
    protected $table = 'unit_multiplier';
    use EnumerationTrait;
    //
}
