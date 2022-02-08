<?php

namespace App\Models;

use App\Contracts\CIM\Wires\DataTypeInterface;
use App\Traits\DataTypeTrait;
use Illuminate\Database\Eloquent\Model;

class Resistance extends Model implements DataTypeInterface
{
    use DataTypeTrait;

    // управляемая таблица
    public $table = 'resistances';


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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function multiplier()
    {
        return $this->belongsTo(\App\Models\UnitMultiplier::class, 'multiplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(\App\Models\UnitSymbol::class, 'unit_id');
    }

    public function getResistance() : Resistance
    {
        return $this;
    }
}
