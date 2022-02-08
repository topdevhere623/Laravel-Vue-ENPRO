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
use App\Traits\OldSwitchInfoTrait;
use App\Contracts\CIM\SwitchInfo\OldSwitchInfoInterface;

/**
 * Class OldSwitchInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\Mass $oilVolumePerTank
 * @property \App\Models\CurrentFlow $withstandCurrent
 * @property \App\Models\CurrentFlow $makingCapacity
 * @property \App\Models\Seconds $enproWithstandCurrentDuration
 * @property \App\Models\Seconds $enproEarthSwitchCurrentDuration
 * @property \App\Models\SecondaryCircuitsVoltageKind $enproSecondaryVoltageKind
 * @property \App\Models\Voltage $enproSecondaryVoltage
 * @property \App\Models\SwitchInfo $SwitchInfo
 * @property \App\Models\BreakerInfo $BreakerInfo
 * @property \App\Models\RecloserInfo $RecloserInfo
 * @property \App\Models\FuseInfo $FuseInfo
 * @property \App\Models\DisconnectorInfo $DisconnectorInfo
 * @property \App\Models\LoadBreakSwitchInfo $LoadBreakSwitchInfo
 * @property boolean $loadBreak
 * @property integer $poleCount
 * @property boolean $remote

 */
class OldSwitchInfo extends BaseModel implements OldSwitchInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use OldSwitchInfoTrait;

    // управляемая таблица
    public $table = 'old_switch_info';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'oil_volume_per_tank_id',
      'withstand_current_id',
      'making_capacity_id',
      'enpro_earth_switch_current_duration_id',
      'enpro_secondary_voltage_kind_id',
      'enpro_secondary_voltage_id',
      'switch_info_id',
      'loadBreak',
      'poleCount',
      'remote',

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
        'oil_volume_per_tank_id' => 'integer',
        'withstand_current_id' => 'integer',
        'making_capacity_id' => 'integer',
        'enpro_earth_switch_current_duration_id' => 'integer',
        'enpro_secondary_voltage_kind_id' => 'integer',
        'enpro_secondary_voltage_id' => 'integer',
        'switch_info_id' => 'integer',
        'loadBreak' => 'boolean',
        'poleCount' => 'integer',
        'remote' => 'boolean',

    ];

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
    public function withstandCurrent()
    {
        return $this->belongsTo(\App\Models\CurrentFlow::class, 'withstand_current_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function makingCapacity()
    {
        return $this->belongsTo(\App\Models\CurrentFlow::class, 'making_capacity_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproWithstandCurrentDuration()
    {
        return $this->belongsTo(\App\Models\Seconds::class, 'enpro_withstand_current_duration_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproEarthSwitchCurrentDuration()
    {
        return $this->belongsTo(\App\Models\Seconds::class, 'enpro_earth_switch_current_duration_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproSecondaryVoltageKind()
    {
        return $this->belongsTo(\App\Models\SecondaryCircuitsVoltageKind::class, 'enpro_secondary_voltage_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enproSecondaryVoltage()
    {
        return $this->belongsTo(\App\Models\Voltage::class, 'enpro_secondary_voltage_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function SwitchInfo()
    {
        return $this->belongsTo(\App\Models\SwitchInfo::class, 'switch_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function BreakerInfo()
    {
        return $this->hasOne(\App\Models\BreakerInfo::class, 'old_switch_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function RecloserInfo()
    {
        return $this->hasOne(\App\Models\RecloserInfo::class, 'old_switch_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function FuseInfo()
    {
        return $this->hasOne(\App\Models\FuseInfo::class, 'old_switch_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function DisconnectorInfo()
    {
        return $this->hasOne(\App\Models\DisconnectorInfo::class, 'old_switch_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function LoadBreakSwitchInfo()
    {
        return $this->hasOne(\App\Models\LoadBreakSwitchInfo::class, 'old_switch_info_id');
    }

    public function getOldSwitchInfo() : OldSwitchInfo
    {
        return $this;
    }
}
