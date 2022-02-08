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
 * Class OldTransformerTankInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\TransformerTankInfo $TransformerTankInfo
 * @property \App\Models\TransformerConstructionKind $constructionKind
 * @property \App\Models\Mass $coreCoilsWeight
 * @property \App\Models\TransformerCoreKind $coreKind
 * @property \App\Models\TransformerFunctionKind $function
 * @property \App\Models\TransformerCoolingKind $coolingKind
 * @property \App\Models\Mass $enproFullWeight
 * @property \App\Models\Mass $enproOilWeight
 * @property \App\Models\TemperatureRange $enproTemperatureRange
 * @property \App\Models\Gost $enproGost
 */
class OldTransformerTankInfo extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    public $parentAssetInfo = null;

    const KIND_MODELS = [
        'constructionKind' => ['modelName' => 'TransformerConstructionKind', 'title' => 'Тип конструкции'],
        'coreKind' => ['modelName' => 'TransformerCoreKind', 'title' => 'Тип сердечника'],
        'function' => ['modelName' => 'TransformerFunctionKind', 'title' => 'Функциональное назначение'],
        'coolingKind' => ['modelName' => 'TransformerCoolingKind', 'title' => 'Вид охлаждения'],
    ];

    // управляемая таблица
    public $table = 'old_transformer_tank_info';

    // мои атрибуты модели
    const title1 = "Силовой трансформатор";
    const title2 = "Силовые трансформаторы";

    // список полей, разрешенных на редактирование
    public $fillable = [
      'transformer_tank_info_id',
      'construction_kind_id',
      'core_coils_weight_id',
      'core_kind_id',
      'function_id',
      'cooling_kind_id',
      'enpro_full_weight_id',
      'enpro_oil_weight_id',
      'enpro_temperature_range_id',
      'gost_id',

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
        'transformer_tank_info_id' => 'integer',
        'construction_kind_id' => 'integer',
        'core_coils_weight_id' => 'integer',
        'core_kind_id' => 'integer',
        'function_id' => 'integer',
        'cooling_kind_id' => 'integer',
        'enpro_full_weight_id' => 'integer',
        'enpro_oil_weight_id' => 'integer',
        'enpro_temperature_range_id' => 'integer',
        'gost_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function TransformerTankInfo()
    {
        return $this->belongsTo(\App\Models\TransformerTankInfo::class, 'transformer_tank_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function constructionKind()
    {
        return $this->belongsTo(\App\Models\TransformerConstructionKind::class, 'construction_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coreCoilsWeight()
    {
        return $this->belongsTo(\App\Models\Mass::class, 'core_coils_weight_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coreKind()
    {
        return $this->belongsTo(\App\Models\TransformerCoreKind::class, 'core_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function function()
    {
        return $this->belongsTo(\App\Models\TransformerFunctionKind::class, 'function_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coolingKind()
    {
        return $this->belongsTo(\App\Models\TransformerCoolingKind::class, 'cooling_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproFullWeight()
    {
        return $this->belongsTo(\App\Models\Mass::class, 'enpro_full_weight_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproOilWeight()
    {
        return $this->belongsTo(\App\Models\Mass::class, 'enpro_oil_weight_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproTemperatureRange()
    {
        return $this->belongsTo(\App\Models\TemperatureRange::class, 'enpro_temperature_range_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproGost()
    {
        return $this->belongsTo(\App\Models\Gost::class, 'gost_id');
    }

    public function getOldTransformerTankInfo() : OldTransformerTankInfo
    {
        return $this;
    }
}
