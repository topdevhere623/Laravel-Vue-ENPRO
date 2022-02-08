<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;



/**
 * Class Pressure
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\UnitMultiplier $multiplier
 * @property \App\Models\UnitSymbol $unit
 * @property float $value

 */
class Pressure extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;


    // управляемая таблица
    public $table = 'pressure';

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



    public function getPressure() : Pressure
    {
        return $this;
    }
}
