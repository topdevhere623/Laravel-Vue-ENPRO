<?php

namespace App\Models;

use App\Contracts\CIM\Wires\EnumerationInterface;
use App\Traits\EnumerationTrait;
use Illuminate\Database\Eloquent\Model;

class PhaseCode extends Model implements EnumerationInterface
{
    protected $table = 'phasecode';
    use EnumerationTrait;
    //
}
