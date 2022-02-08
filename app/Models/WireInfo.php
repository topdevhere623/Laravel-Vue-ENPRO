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
use App\Traits\WireInfoTrait;
use App\Contracts\CIM\AssetInfo\WireInfoInterface;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;
use App\Models\CableInfo;
use App\Models\OverheadWireInfo;

/**
 * Class WireInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\Length $coreRadius
 * @property \App\Models\Length $gmr
 * @property \App\Models\WireInsulationKind $insulationMaterial
 * @property \App\Models\Length $insulationThickness
 * @property \App\Models\WireMaterialKind $material
 * @property \App\Models\ResistancePerLength $rAC25
 * @property \App\Models\ResistancePerLength $rAC50
 * @property \App\Models\ResistancePerLength $rAC75
 * @property \App\Models\Length $radius
 * @property \App\Models\CurrentFlow $ratedCurrent
 * @property \App\Models\ResistancePerLength $rDC20
 * @property \App\Models\AssetInfo $AssetInfo
 * @property \App\Models\EnproWeightPerLength $enproWeightPerLength
 * @property \App\Models\EnproForce $enproBreakForce
 * @property \App\Models\Gost $enproGost
 * @property \App\Models\Voltage $nominalVoltage
 * @property \App\Models\Duration $standardServiceLife
 * @property integer $coreStrandCount
 * @property integer $gost_id
 * @property integer $fire_safety_id
 * @property boolean $insulated
 * @property string $sizeDescription
 * @property integer $strandCount
 * @property CableInfo $CableInfo
 * @property OverheadWireInfo $OverheadWireInfo

 */
class WireInfo extends BaseModel implements WireInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use WireInfoTrait;
    use NestedUpdatable;
    public $parentAssetInfo = null;

    // управляемая таблица
    public $table = 'wire_info';

    // мои атрибуты модели
    const title1 = "Марки проводов";
    const title2 = "Марки проводов";

    // список полей, разрешенных на редактирование
    public $fillable = [
      'core_radius_id',
      'gmr_id',
      'insulation_material_id',
      'insulation_thickness_id',
      'material_id',
      'r_a_c25_id',
      'r_a_c50_id',
      'r_a_c75_id',
      'radius_id',
      'rated_current_id',
      'r_d_c20_id',
      'assetinfo_id',
      'coreStrandCount',
      'insulated',
      'sizeDescription',
      'strandCount',
      'enpro_force_id',
      'enpro_weight_per_length_id',
      'gost_id',
      'nominal_voltage_id',
      'standard_service_life_id',

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
        'core_radius_id' => 'integer',
        'gmr_id' => 'integer',
        'insulation_material_id' => 'integer',
        'insulation_thickness_id' => 'integer',
        'material_id' => 'integer',
        'r_a_c25_id' => 'integer',
        'r_a_c50_id' => 'integer',
        'r_a_c75_id' => 'integer',
        'radius_id' => 'integer',
        'rated_current_id' => 'integer',
        'r_d_c20_id' => 'integer',
        'assetinfo_id' => 'integer',
        'coreStrandCount' => 'integer',
        'insulated' => 'boolean',
        'sizeDescription' => 'string',
        'strandCount' => 'integer',
        'enpro_force_id' => 'integer',
        'enpro_weight_per_length_id' => 'integer',
        'gost_id' => 'integer',
        'nominal_voltage_id' => 'integer',
        'standard_service_life_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coreRadius()
    {
        return $this->belongsTo(\App\Models\Length::class, 'core_radius_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gmr()
    {
        return $this->belongsTo(\App\Models\Length::class, 'gmr_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function insulationMaterial()
    {
        return $this->belongsTo(\App\Models\WireInsulationKind::class, 'insulation_material_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function insulationThickness()
    {
        return $this->belongsTo(\App\Models\Length::class, 'insulation_thickness_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(\App\Models\WireMaterialKind::class, 'material_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rAC25()
    {
        return $this->belongsTo(\App\Models\ResistancePerLength::class, 'r_a_c25_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rAC50()
    {
        return $this->belongsTo(\App\Models\ResistancePerLength::class, 'r_a_c50_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rAC75()
    {
        return $this->belongsTo(\App\Models\ResistancePerLength::class, 'r_a_c75_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function radius()
    {
        return $this->belongsTo(\App\Models\Length::class, 'radius_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratedCurrent()
    {
        return $this->belongsTo(\App\Models\CurrentFlow::class, 'rated_current_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rDC20()
    {
        return $this->belongsTo(\App\Models\ResistancePerLength::class, 'r_d_c20_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproBreakForce() : BelongsTo
    {
        return $this->belongsTo(EnproForce::class, 'enpro_force_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproWeightPerLength() : BelongsTo
    {
        return $this->belongsTo(EnproWeightPerLength::class, 'enpro_weight_per_length_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproGost() : BelongsTo
    {
        return $this->belongsTo(Gost::class, 'gost_id');
    }

    /**
     * @return BelongsTo
     */
    public function nominalVoltage() : BelongsTo
    {
        return $this->belongsTo(Voltage::class, 'nominal_voltage_id');
    }

    /**
     * @return BelongsTo
     */
    public function standardServiceLife() : BelongsTo
    {
        return $this->belongsTo(Duration::class, 'standard_service_life_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function AssetInfo()
    {
        return $this->belongsTo(\App\Models\AssetInfo::class, 'asset_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OverheadWireInfo()
    {
        return $this->hasOne(\App\Models\OverheadWireInfo::class, 'wire_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function CableInfo()
    {
        return $this->hasOne(\App\Models\CableInfo::class, 'wire_info_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (WireInfo $model) {
            $AssetInfo = $model->getAssetInfo();
            if (! empty($AssetInfo)) {
                $AssetInfo->save();
                $model->AssetInfo()->associate($AssetInfo);
            };



        });

    }


    public function getWireInfo() : WireInfo
    {
        return $this;
    }
}
