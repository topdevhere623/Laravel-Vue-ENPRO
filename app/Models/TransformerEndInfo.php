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
use App\Traits\TransformerEndInfoTrait;
use App\Contracts\CIM\OldTransformerEndInfo\TransformerEndInfoInterface;

/**
 * Class TransformerEndInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\WindingConnection $connectionKind
 * @property \App\Models\ApparentPower $ratedS
 * @property \App\Models\Voltage $ratedU
 * @property \App\Models\Resistance $r
 * @property \App\Models\TransformerTankInfo $TransformerTankInfo
 * @property \App\Models\AssetInfo $AssetInfo
 * @property integer $endNumber
 * @property integer $phaseAngleClock
 * @property \App\Models\NoLoadTest[] $NoLoadTests
 * @property \App\Models\ShortCircuitTest[] $ShortCircuitTests
 * @property \App\Models\OldTransformerEndInfo $OldTransformerEndInfo
 */
class TransformerEndInfo extends BaseModel implements TransformerEndInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use TransformerEndInfoTrait;

    // управляемая таблица
    public $table = 'transformer_end_info';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'connection_kind_id',
      'rated_s_id',
      'rated_u_id',
      'r_id',
      'transformer_tank_info_id',
      'asset_info_id',
      'endNumber',
      'phaseAngleClock',

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
        'connection_kind_id' => 'integer',
        'rated_s_id' => 'integer',
        'rated_u_id' => 'integer',
        'r_id' => 'integer',
        'transformer_tank_info_id' => 'integer',
        'asset_info_id' => 'integer',
        'endNumber' => 'integer',
        'phaseAngleClock' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function connectionKind()
    {
        return $this->belongsTo(\App\Models\WindingConnection::class, 'connection_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratedS()
    {
        return $this->belongsTo(\App\Models\ApparentPower::class, 'rated_s_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratedU()
    {
        return $this->belongsTo(\App\Models\Voltage::class, 'rated_u_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function r()
    {
        return $this->belongsTo(\App\Models\Resistance::class, 'r_id');
    }

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
    public function AssetInfo()
    {
        return $this->belongsTo(\App\Models\AssetInfo::class, 'asset_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function NoLoadTests()
    {
        return $this->hasMany(\App\Models\NoLoadTest::class, 'energised_end_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ShortCircuitTests()
    {
        return $this->hasMany(\App\Models\ShortCircuitTest::class, 'energised_end_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OldTransformerEndInfo()
    {
        return $this->hasOne(\App\Models\OldTransformerEndInfo::class, 'transformer_end_info_id');
    }

    public function getTransformerEndInfo() : TransformerEndInfo
    {
        return $this;
    }
}
