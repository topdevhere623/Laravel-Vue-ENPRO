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
use App\Traits\SwitchInfoTrait;
use App\Contracts\CIM\SwitchInfo\SwitchInfoInterface;

/**
 * Class SwitchInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\BreakerConstructionKind $enproBreakerKind
 * @property \App\Models\InterrupterPositionKind $enproInterrupterPosition
 * @property \App\Models\Voltage $ratedVoltage
 * @property \App\Models\Voltage $enproMaxVoltage
 * @property \App\Models\Frequency $ratedFrequency
 * @property \App\Models\CurrentFlow $ratedCurrent
 * @property \App\Models\CurrentFlow $breakingCapacity
 * @property \App\Models\Seconds $ratedInterruptingTime
 * @property \App\Models\Voltage $ratedImpulseWithstandVoltage
 * @property \App\Models\Pressure $enproRatedPressure
 * @property \App\Models\Pressure $lowPressureAlarm
 * @property \App\Models\Pressure $lowPressureLockOut
 * @property \App\Models\EnproForce $enproBreakForce
 * @property \App\Models\Length $enproInsulationLength
 * @property \App\Models\GostClimaticModPlacementKind $enproClimaticModPlacement
 * @property \App\Models\TemperatureRange $enproTemperatureRange
 * @property \App\Models\Gost $enproGost
 * @property \App\Models\Mass $gasWeightPerTank
 * @property \App\Models\Mass $oilVolumePerTank
 * @property \App\Models\AssetInfo $AssetInfo
 * @property \App\Models\OldSwitchInfo $OldSwitchInfo
 * @property boolean $isSinglePhase
 * @property boolean $isUnganged

 */
class SwitchInfo extends BaseModel implements SwitchInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use SwitchInfoTrait;

    const KIND_MODELS = [
        'enproBreakerKind' => ['modelName' => 'BreakerConstructionKind', 'idName' => 'enpro_breaker_kind_id', 'title' => 'Принцип гашения дуги'],
        'enproInterrupterPosition' => ['modelName' => 'InterrupterPositionKind', 'idName' => 'enpro_interrupter_position_id', 'title' => 'Вид размещения ДГУ'],
        'enproClimaticModPlacement' => ['modelName' => 'GostClimaticModPlacementKind', 'idName' => 'enpro_climatic_mod_placement_id', 'title' => 'Климатическое исполнение и категория размещения'],
        'enproSecondaryVoltageKind' => ['modelName' => 'SecondaryCircuitsVoltageKind', 'idName' => 'enpro_secondary_voltage_kind_id', 'title' => 'Номинальная частота питания вкл. и отк. устройств, вспом. цепей и цепей упр.'],
    ];

    const CHILD_MODELS = [
        'BreakerInfo',
        'LoadBreakSwitchInfo',
        'DisconnectorInfo',
        'FuseInfo',
        'RecloserInfo',
    ];

    // управляемая таблица
    public $table = 'switch_info';

    // список полей, разрешенных на редактирование
    public $fillable = [
        'enpro_breaker_kind_id',
        'enpro_interrupter_position_id',
        'rated_voltage_id',
        'enpro_max_voltage_id',
        'rated_frequency_id',
        'rated_current_id',
        'breaking_capacity_id',
        'rated_interrupting_time_id',
        'rated_impulse_withstand_voltage_id',
        'enpro_rated_pressure_id',
        'low_pressure_alarm_id',
        'low_pressure_lock_out_id',
        'enpro_insulation_length_id',
        'enpro_climatic_mod_placement_id',
        'enpro_temperature_range_id',
        'enpro_gost_id',
        'gas_weight_per_tank_id',
        'oil_volume_per_tank_id',
        'assetinfo_id',
        'isSinglePhase',
        'isUnganged',
        'enpro_break_force_id',

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
        'enpro_breaker_kind_id' => 'integer',
        'enpro_interrupter_position_id' => 'integer',
        'rated_voltage_id' => 'integer',
        'enpro_max_voltage_id' => 'integer',
        'rated_frequency_id' => 'integer',
        'rated_current_id' => 'integer',
        'breaking_capacity_id' => 'integer',
        'rated_interrupting_time_id' => 'integer',
        'rated_impulse_withstand_voltage_id' => 'integer',
        'enpro_rated_pressure_id' => 'integer',
        'low_pressure_alarm_id' => 'integer',
        'low_pressure_lock_out_id' => 'integer',
        'enpro_insulation_length_id' => 'integer',
        'enpro_climatic_mod_placement_id' => 'integer',
        'enpro_temperature_range_id' => 'integer',
        'enpro_gost_id' => 'integer',
        'gas_weight_per_tank_id' => 'integer',
        'oil_volume_per_tank_id' => 'integer',
        'assetinfo_id' => 'integer',
        'isSinglePhase' => 'boolean',
        'isUnganged' => 'boolean',
        'enpro_break_force_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproBreakerKind()
    {
        return $this->belongsTo(\App\Models\BreakerConstructionKind::class, 'enpro_breaker_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproInterrupterPosition()
    {
        return $this->belongsTo(\App\Models\InterrupterPositionKind::class, 'enpro_interrupter_position_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratedVoltage()
    {
        return $this->belongsTo(\App\Models\Voltage::class, 'rated_voltage_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproMaxVoltage()
    {
        return $this->belongsTo(\App\Models\Voltage::class, 'enpro_max_voltage_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratedFrequency()
    {
        return $this->belongsTo(\App\Models\Frequency::class, 'rated_frequency_id');
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
    public function breakingCapacity()
    {
        return $this->belongsTo(\App\Models\CurrentFlow::class, 'breaking_capacity_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratedInterruptingTime()
    {
        return $this->belongsTo(\App\Models\Seconds::class, 'rated_interrupting_time_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratedImpulseWithstandVoltage()
    {
        return $this->belongsTo(\App\Models\Voltage::class, 'rated_impulse_withstand_voltage_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproRatedPressure()
    {
        return $this->belongsTo(\App\Models\Pressure::class, 'enpro_rated_pressure_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lowPressureAlarm()
    {
        return $this->belongsTo(\App\Models\Pressure::class, 'low_pressure_alarm_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lowPressureLockOut()
    {
        return $this->belongsTo(\App\Models\EnproForce::class, 'low_pressure_lock_out_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproBreakForce()
    {
        return $this->belongsTo(\App\Models\EnproForce::class, 'enpro_break_force_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproInsulationLength()
    {
        return $this->belongsTo(\App\Models\Length::class, 'enpro_insulation_length_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproClimaticModPlacement()
    {
        return $this->belongsTo(\App\Models\GostClimaticModPlacementKind::class, 'enpro_climatic_mod_placement_id');
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
        return $this->belongsTo(\App\Models\Gost::class, 'enpro_gost_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gasWeightPerTank()
    {
        return $this->belongsTo(\App\Models\Mass::class, 'gas_weight_per_tank_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function oilVolumePerTank()
    {
        return $this->belongsTo(\App\Models\Mass::class, 'oil_volume_per_tank_id');
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
    public function OldSwitchInfo()
    {
        return $this->hasOne(\App\Models\OldSwitchInfo::class, 'switch_info_id');
    }

    public function getSwitchInfo() : SwitchInfo
    {
        return $this;
    }
}
