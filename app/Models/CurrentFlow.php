<?php

namespace App\Models;

use App\Contracts\CIM\Wires\DataTypeInterface;
use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Model;

class CurrentFlow extends Model implements DataTypeInterface
{
    use DataTypeTrait;
    //

    public $fillable = [
        'value',
        'multiplier_id',
        'unit_id',
    ];

    public function getCurrentFlow() : CurrentFlow
    {
        return $this;
    }
}
