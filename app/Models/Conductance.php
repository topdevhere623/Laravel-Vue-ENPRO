<?php

namespace App\Models;

use App\Contracts\CIM\Wires\DataTypeInterface;
use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Model;

class Conductance extends Model implements DataTypeInterface
{
    use DataTypeTrait;
    //
}
