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
use App\Traits\BreakerInfoTrait;
use App\Contracts\CIM\SwitchInfo\BreakerInfoInterface;

/**
 * Class BreakerInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\OldSwitchInfo $OldSwitchInfo

 */
class BreakerInfo extends BaseModel implements BreakerInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use BreakerInfoTrait;

    // управляемая таблица
    public $table = 'breaker_info';

    const title1 = "Выключатель 3-750 кВ";
    const title2 = "Выключатели 3-750 кВ";

    // список полей, разрешенных на редактирование
    public $fillable = [
      'old_switch_info_id',

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
        'old_switch_info_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OldSwitchInfo()
    {
        return $this->belongsTo(\App\Models\OldSwitchInfo::class, 'old_switch_info_id');
    }



    public function getBreakerInfo() : BreakerInfo
    {
        return $this;
    }
}
