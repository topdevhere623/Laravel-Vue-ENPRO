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
use App\Traits\RecloserInfoTrait;
use App\Contracts\CIM\SwitchInfo\RecloserInfoInterface;

/**
 * Class RecloserInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\OldSwitchInfo $OldSwitchInfo

 */
class RecloserInfo extends BaseModel implements RecloserInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use RecloserInfoTrait;

    // управляемая таблица
    public $table = 'recloser_info';

    const title1 = "Реклоузер 6-35 кВ";
    const title2 = "Реклоузеры 6-35 кВ";

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



    public function getRecloserInfo() : RecloserInfo
    {
        return $this;
    }
}
