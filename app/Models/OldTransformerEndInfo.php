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
use App\Traits\OldTransformerEndInfoTrait;
use App\Contracts\CIM\OldTransformerEndInfo\OldTransformerEndInfoInterface;


/**
 * Class OldTransformerEndInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\TransformerEndInfo $TransformerEndInfo
 * @property \App\Models\WindingInsulationKind $windingInsulationKind
 */
class OldTransformerEndInfo extends BaseModel implements OldTransformerEndInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use OldTransformerEndInfoTrait;

    // управляемая таблица
    public $table = 'old_transformer_end_info';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'transformer_end_info_id',
      'winding_insulation_kind_id',
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
        'transformer_end_info_id' => 'integer',
        'winding_insulation_kind_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function TransformerEndInfo()
    {
        return $this->belongsTo(\App\Models\TransformerEndInfo::class, 'transformer_end_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function windingInsulationKind()
    {
        return $this->belongsTo(\App\Models\WindingInsulationKind::class, 'winding_insulation_kind_id');
    }


    public function getOldTransformerEndInfo() : OldTransformerEndInfo
    {
        return $this;
    }
}
