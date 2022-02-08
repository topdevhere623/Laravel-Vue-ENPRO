<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;

/**
 * Class WireInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property int $phase_info_id
 * @property \App\Models\SinglePhaseKind $phaseInfo
 * @property \App\Models\WireInfo $WireInfo

 */
class WirePhaseInfo extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    public $parentAssetInfo = null;

    // управляемая таблица
    public $table = 'wire_phase_info';

    // мои атрибуты модели
    const title1 = "Фаза проводника";
    const title2 = "Фазы проводника";

    // список полей, разрешенных на редактирование
    public $fillable = [
      'phase_info_id',
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
        'phase_info_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function phaseInfo()
    {
        return $this->belongsTo(\App\Models\SinglePhaseKind::class, 'phase_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function WireInfo()
    {
        return $this->hasOne(\App\Models\WireInfo::class, 'wire_phase_info_id');
    }

    public function getWirePhaseInfo() : WirePhaseInfo
    {
        return $this;
    }
}
