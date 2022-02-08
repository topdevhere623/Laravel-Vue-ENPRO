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


/**
 * Class OverheadWireInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\WireInfo $WireInfo

 */
class OverheadWireInfo extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    public $table = 'overhead_wire_info';

    // мои атрибуты модели
    const title1 = "Марка провода";
    const title2 = "Марки проводов";

    // список полей, разрешенных на редактирование
    public $fillable = [
      'wire_info_id',
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
        'wire_info_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function WireInfo()
    {
        return $this->belongsTo(\App\Models\WireInfo::class, 'wire_info_id');
    }

    public function getOverheadWireInfo() : OverheadWireInfo
    {
        return $this;
    }
}
