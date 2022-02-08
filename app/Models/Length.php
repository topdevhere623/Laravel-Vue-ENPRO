<?php

namespace App\Models;

use App\Contracts\CIM\Wires\DataTypeInterface;
use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Model;

class Length extends Model implements DataTypeInterface
{
    protected $table = 'lengths';

    use DataTypeTrait;
    //
    public $fillable = [
        'value',
        'multiplier_id',
        'unit_id',
    ];

}
