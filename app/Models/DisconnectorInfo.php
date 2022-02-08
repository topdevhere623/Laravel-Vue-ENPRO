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
use App\Traits\DisconnectorInfoTrait;
use App\Contracts\CIM\SwitchInfo\DisconnectorInfoInterface;

/**
 * Class DisconnectorInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\OldSwitchInfo $OldSwitchInfo

 */
class DisconnectorInfo extends BaseModel implements DisconnectorInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use DisconnectorInfoTrait;

    // управляемая таблица
    public $table = 'disconnectorinfo';

    const title1 = "Разъединитель выше 1 кВ";
    const title2 = "Разъединители выше 1 кВ";

    // список полей, разрешенных на редактирование
    public $fillable = [
      'old_switch_info_id',
      'drive_id',
      'ASSETINFOKEY',
      'UNOM',
      'IPKST',
      'TTERMSTGL',
      'TYPEPRIVGL',
      'VOLTAGEID',
      'UMAX',
      'ITERMSTGL',
      'ISKVZAZ',
      'ITERMSTZAZ',
      'TTERMSTZAZ',
      'MASSA',
      'DRIVEMARK',
      'REMARK',
      'status',
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



    public function getDisconnectorInfo() : DisconnectorInfo
    {
        return $this;
    }
}
