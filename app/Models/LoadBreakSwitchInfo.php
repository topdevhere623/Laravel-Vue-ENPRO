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
use App\Traits\LoadBreakSwitchInfoTrait;
use App\Contracts\CIM\SwitchInfo\LoadBreakSwitchInfoInterface;

/**
 * Class LoadBreakSwitchInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\OldSwitchInfo $OldSwitchInfo

 */
class LoadBreakSwitchInfo extends BaseModel implements LoadBreakSwitchInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use LoadBreakSwitchInfoTrait;

    // управляемая таблица
    public $table = 'load_break_switch_info';

    const title1 = "Выключатель нагрузки 3-10 кВ";
    const title2 = "Выключатели нагрузки 3-10 кВ";

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



    public function getLoadBreakSwitchInfo() : LoadBreakSwitchInfo
    {
        return $this;
    }
}
