<?php

namespace App\Models;

use App\Contracts\CIM\PotentialTransformerInfo\PotentialTransformerInfoInterface;
use App\Models\BaseModel as BaseModel;
use App\Traits\PotentialTransformerInfoTrait;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $asset_info_id
 * @property integer $rated_voltage_id
 * @property integer $rated_frequency_id
 * @property integer $enpro_secondary1_voltage_id
 * @property integer $enpro_secondary2_voltage_id
 * @property integer $enpro_construction_kind_id
 * @property integer $enpro_climatic_mod_placement_id
 * @property string $accuracyclass
 * @property float $massa
 * @property AssetInfo $AssetInfo
 * @property Frequency $ratedFrequency
 * @property GostClimaticModPlacementKind $enproClimaticModPlacement
 * @property PotentialTransformerKind $enproConstructionKind
 * @property Voltage $enproSecondary1Voltage
 * @property Voltage $enproSecondary2Voltage
 * @property Voltage $ratedVoltage
 */
class PotentialTransformerInfo extends BaseModel implements PotentialTransformerInfoInterface
{
    use SoftDeletes;
    use NestedUpdatable;

    use PotentialTransformerInfoTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'potential_transformer_info';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = [
        'asset_info_id',
        'rated_voltage_id',
        'rated_frequency_id',
        'enpro_secondary1_voltage_id',
        'enpro_secondary2_voltage_id',
        'enpro_construction_kind_id',
        'enpro_climatic_mod_placement_id',
        'accuracyclass',
        'massa'
    ];

    /**
     * @return BelongsTo
     */
    public function AssetInfo()
    {
        return $this->belongsTo('App\Models\AssetInfo');
    }

    /**
     * @return BelongsTo
     */
    public function ratedFrequency()
    {
        return $this->belongsTo('App\Models\Frequency', 'rated_frequency_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproClimaticModPlacement()
    {
        return $this->belongsTo('App\Models\GostClimaticModPlacementKind', 'enpro_climatic_mod_placement_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproConstructionKind()
    {
        return $this->belongsTo('App\Models\PotentialTransformerKind', 'enpro_construction_kind_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproSecondary1Voltage()
    {
        return $this->belongsTo('App\Models\Voltage', 'enpro_secondary1_voltage_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproSecondary2Voltage()
    {
        return $this->belongsTo('App\Models\Voltage', 'enpro_secondary2_voltage_id');
    }

    /**
     * @return BelongsTo
     */
    public function ratedVoltage()
    {
        return $this->belongsTo('App\Models\Voltage', 'rated_voltage_id');
    }

    public function getPotentialTransformerInfo() : PotentialTransformerInfo
    {
        return $this;
    }
}
