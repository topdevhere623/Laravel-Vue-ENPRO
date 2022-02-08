<?php

namespace App\Models;

use App\Contracts\CIM\Wires\EnumerationInterface;
use App\Traits\EnumerationTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SinglePhaseKind
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property string $literal
 */

class SinglePhaseKind extends Model implements EnumerationInterface
{
    use EnumerationTrait;
    //

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'literal' => 'string',

    ];
}
