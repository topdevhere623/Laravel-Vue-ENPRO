<?php

namespace App\Models;

use App\Contracts\CIM\Wires\DataTypeInterface;
use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Temperature
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property float $value

 */

class Temperature extends BaseModel implements DataTypeInterface
{
    use DataTypeTrait;

    // управляемая таблица
    public $table = 'temperatures';

    // список полей, разрешенных на редактирование
    public $fillable = [
        'multiplier_id',
        'unit_id',
        'value',

    ];

    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'multiplier_id' => 'integer',
        'unit_id' => 'integer',
        'value' => 'float',

    ];

    public function getTemperature() : Temperature
    {
        return $this;
    }
}
