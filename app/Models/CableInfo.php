<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CableInfoTrait;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;
use App\Contracts\CIM\AssetInfo\CableInfoInterface;

/**
 * Class CableInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\CableConstructionKind $constructionKind
 * @property \App\Models\Length $diameterOverCore
 * @property \App\Models\Length $diameterOverInsulation
 * @property \App\Models\Length $diameterOverJacket
 * @property \App\Models\Length $diameterOverScreen
 * @property \App\Models\Temperature $nominalTemperature
 * @property \App\Models\CableOuterJacketKind $outerJacketKind
 * @property \App\Models\CableShieldMaterialKind $shieldMaterial
 * @property \App\Models\WireInfo $WireInfo
 * @property \App\Models\EnproFireSafetyKind $fireSafety
 * @property boolean $isStrandFill
 * @property boolean $sheathAsNeutral

 */
class CableInfo extends BaseModel implements CableInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use CableInfoTrait;
    use NestedUpdatable;
    public $parentWireInfo = null;

    // управляемая таблица
    public $table = 'cable_info';

    // мои атрибуты модели
    const title1 = "Марка кабеля";
    const title2 = "Марки кабелей";

    // список полей, разрешенных на редактирование
    public $fillable = [
      'construction_kind_id',
      'diameter_over_core_id',
      'diameter_over_insulation_id',
      'diameter_over_jacket_id',
      'diameter_over_screen_id',
      'nominal_temperature_id',
      'outer_jacket_kind_id',
      'shield_material_id',
      'fire_safety_id',
      'wire_info_id',
      'isStrandFill',
      'sheathAsNeutral',

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
        'construction_kind_id' => 'integer',
        'diameter_over_core_id' => 'integer',
        'diameter_over_insulation_id' => 'integer',
        'diameter_over_jacket_id' => 'integer',
        'diameter_over_screen_id' => 'integer',
        'nominal_temperature_id' => 'integer',
        'outer_jacket_kind_id' => 'integer',
        'shield_material_id' => 'integer',
        'fire_safety_id' => 'integer',
        'wire_info_id' => 'integer',
        'isStrandFill' => 'boolean',
        'sheathAsNeutral' => 'boolean',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function constructionKind()
    {
        return $this->belongsTo(\App\Models\CableConstructionKind::class, 'construction_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diameterOverCore()
    {
        return $this->belongsTo(\App\Models\Length::class, 'diameter_over_core_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diameterOverInsulation()
    {
        return $this->belongsTo(\App\Models\Length::class, 'diameter_over_insulation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diameterOverJacket()
    {
        return $this->belongsTo(\App\Models\Length::class, 'diameter_over_jacket_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diameterOverScreen()
    {
        return $this->belongsTo(\App\Models\Length::class, 'diameter_over_screen_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nominalTemperature()
    {
        return $this->belongsTo(\App\Models\Temperatures::class, 'nominal_temperature_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function outerJacketKind()
    {
        return $this->belongsTo(\App\Models\CableOuterJacketKind::class, 'outer_jacket_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shieldMaterial()
    {
        return $this->belongsTo(\App\Models\CableShieldMaterialKind::class, 'shield_material_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function WireInfo()
    {
        return $this->belongsTo(\App\Models\WireInfo::class, 'wire_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fireSafety()
    {
        return $this->belongsTo(\App\Models\EnproFireSafetyKind::class, 'fire_safety_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (CableInfo $model) {
            $WireInfo = $model->getWireInfo();
            if (! empty($WireInfo)) {
                $WireInfo->save();
                $model->WireInfo()->associate($WireInfo);
            };
        });
    }


    public function getCableInfo() : CableInfo
    {
        return $this;
    }
}
