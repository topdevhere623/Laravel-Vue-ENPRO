<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\WireAssemblyInfoTrait;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;
use App\Contracts\CIM\AssetInfo\WireAssemblyInfoInterface;

/**
 * Class TemperatureRange
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\Temperature $minTemperature
 * @property \App\Models\Temperature $maxTemperature
 */
class TemperatureRange extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;

    // управляемая таблица
    public $table = 'enpro_temperature_range';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'min_temperature_id',
      'max_temperature_id',

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
        'min_temperature_id' => 'integer',
        'max_temperature_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function minTemperature()
    {
        return $this->belongsTo(\App\Models\Temperature::class, 'min_temperature_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maxTemperature()
    {
        return $this->belongsTo(\App\Models\Temperature::class, 'max_temperature_id');
    }

    public function getTemperatureRange() : TemperatureRange
    {
        return $this;
    }
}
