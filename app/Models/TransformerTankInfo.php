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
 * Class CableInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\AssetInfo $AssetInfo
 * @property \App\Models\TransformerEndInfo[] $TransformerEndInfo
 */
class TransformerTankInfo extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;

    // управляемая таблица
    public $table = 'transformer_tank_info';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'asset_info_id',

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
        'asset_info_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function AssetInfo()
    {
        return $this->belongsTo(\App\Models\AssetInfo::class, 'asset_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function TransformerEndInfo()
    {
        return $this->hasMany(\App\Models\TransformerEndInfo::class, 'transformer_tank_info_id');
    }

    public function getTransformerTankInfo() : TransformerTankInfo
    {
        return $this;
    }
}
