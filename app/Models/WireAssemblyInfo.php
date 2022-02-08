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
 * @property \App\Models\WirePhaseInfo[] $WirePhaseInfo
 * @property \App\Models\AssetInfo $AssetInfo
 */
class WireAssemblyInfo extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    public $parentAssetInfo = null;

    const KIND_MODELS = [
        'outerJacketKind' => ['modelName' => 'CableOuterJacketKind', 'idName' => 'outer_jacket_kind_id', 'title' => 'Материал наружной оболочки'],
        'shieldMaterial' => ['modelName' => 'CableShieldMaterialKind', 'idName' => 'shield_material_id', 'title' => 'Вид материала экрана'],
        'fireSafety' => ['modelName' => 'EnproFireSafetyKind', 'idName' => 'fire_safety_id', 'title' => 'Исполнение по пожароопасности'],
        'constructionKind' => ['modelName' => 'CableConstructionKind', 'idName' => 'construction_kind_id', 'title' => 'Конструктивное исполнение токопроводящих жил'],
        'material' => ['modelName' => 'WireMaterialKind', 'idName' => 'material_id', 'title' => 'Материал'],
        'insulationMaterial' => ['modelName' => 'WireInsulationKind', 'idName' => 'insulation_material_id', 'title' => 'Материал изоляции'],
    ];

    const ENUM_KIND_MODELS = [
        'phaseInfo' => ['modelName' => 'SinglePhaseKind', 'idName' => 'phase_info_id', 'title' => 'Фаза'],
    ];

    const CHILD_MODELS = [
        'CableInfo',
        'OverheadWireInfo',
    ];

    // управляемая таблица
    public $table = 'wire_assembly_info';

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function WirePhaseInfo()
    {
        return $this->hasMany(\App\Models\WirePhaseInfo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function AssetInfo()
    {
        return $this->belongsTo(\App\Models\AssetInfo::class, 'asset_info_id');
    }



   /* protected static function boot()
    {
        parent::boot();

        static::creating(function (WireAssemblyInfo $model) {
            $AssetInfo = $model->getAssetInfo();
            if (! empty($AssetInfo)) {
                $AssetInfo->save();
                $model->assetInfo()->associate($AssetInfo);
            };
        });
    }*/


    public function getWireAssemblyInfo() : WireAssemblyInfo
    {
        return $this;
    }
}
