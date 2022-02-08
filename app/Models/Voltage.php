<?php

namespace App\Models;

use App\Contracts\CIM\Wires\VoltageInterface;
use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Model;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;

class Voltage extends Model implements VoltageInterface
{
    use DataTypeTrait;
    use NestedUpdatable;

    public $fillable = [
        'value',
        'multiplier_id',
        'unit_id',
    ];

    //
    public function getValue(): int
    {
        return $this->value;
    }
}
