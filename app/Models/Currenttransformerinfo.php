<?php

namespace App\Models;

use App\Contracts\CIM\Currenttransformerinfo\CurrenttransformerinfoInterface;
use App\Traits\CurrenttransformerinfoTrait;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel as BaseModel;

// трайты
use App\Traits\CommonTrait;

// модели


/**
 * @property integer $id
 * @property integer $basevoltage_id
 * @property integer $asset_info_id
 * @property integer $rated_voltage_id
 * @property integer $enpro_max_voltage_id
 * @property integer $rated_frequency_id
 * @property integer $nominal_ratio_id
 * @property integer $rated_current_id
 * @property integer $enpro_climatic_mod_placement_id
 * @property string $assetinfokey
 * @property float $umax
 * @property float $inom1
 * @property float $inom2
 * @property float $ielst
 * @property float $f
 * @property float $snom_z
 * @property float $iprkro_z
 * @property float $ikrt_1
 * @property float $ikrt_3
 * @property float $ikrelst
 * @property float $uisp_1
 * @property float $uisp_gi
 * @property float $iprkro_15
 * @property float $unom_do
 * @property float $iterm_1
 * @property float $iterm_3
 * @property float $massa
 * @property string $n_vo
 * @property string $klasst_z
 * @property string $klasst_iz
 * @property string $snom_iz
 * @property float $unom
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $accuracyclass
 * @property int $corecount
 * @property AssetInfo $AssetInfo
 * @property Basevoltage $basevoltage
 * @property GostClimaticModPlacementKind $enproClimaticModPlacement
 * @property Voltage $enproMaxVoltage
 * @property Ratio $nominalRatio
 * @property CurrentFlow $ratedCurrent
 * @property Frequency $ratedFrequency
 * @property Voltage $ratedVoltage
 */
class Currenttransformerinfo extends BaseModel implements CurrenttransformerinfoInterface
{
    // подключение трайтов
    //use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;

    use CurrenttransformerinfoTrait;

    // управляемая таблица
    protected $table = "currenttransformerinfo";

    // список полей, разрешенных на редактирование
    protected $fillable = [
        'basevoltage_id',
        'asset_info_id',
        'rated_voltage_id',
        'enpro_max_voltage_id',
        'rated_frequency_id',
        'nominal_ratio_id',
        'rated_current_id',
        'enpro_climatic_mod_placement_id',

        'assetinfokey',
        'umax',
        'inom1',
        'inom2',
        'ielst',
        'f',
        'snom_z',
        'iprkro_z',
        'ikrt_1',
        'ikrt_3',
        'ikrelst',
        'uisp_1',
        'uisp_gi',
        'iprkro_15',
        'unom_do',
        'iterm_1',
        'iterm_3',
        'massa',
        'n_vo',
        'klasst_z',
        'klasst_iz',
        'snom_iz',
        'unom',
        'accuracyclass',
        'corecount'
    ];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Информация о трансформаторе";
    const title2 = "Информация о трансформаторах";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/currenttransformerinfo/' . $this->id;
    }

    // связи
    public function AssetInfo()
    {
        return $this->belongsTo(\App\Models\AssetInfo::class, 'asset_info_id');
    }

    public function basevoltage()
    {
        return $this->belongsTo(\App\Models\BaseVoltage::class, 'basevoltage_id');
    }

    public function enproClimaticModPlacement()
    {
        return $this->belongsTo(\App\Models\GostClimaticModPlacementKind::class, 'enpro_climatic_mod_placement_id');
    }

    public function enproMaxVoltage()
    {
        return $this->belongsTo(\App\Models\Voltage::class, 'enpro_max_voltage_id');
    }

    public function nominalRatio()
    {
        return $this->belongsTo(\App\Models\Ratio::class, 'nominal_ratio_id');
    }

    public function ratedCurrent()
    {
        return $this->belongsTo(\App\Models\CurrentFlow::class, 'rated_current_id');
    }

    public function ratedFrequency()
    {
        return $this->belongsTo(\App\Models\Frequency::class, 'rated_frequency_id');
    }

    public function ratedVoltage()
    {
        return $this->belongsTo(\App\Models\Voltage::class, 'rated_voltage_id');
    }

    public function getCurrentTransformerInfo(): Currenttransformerinfo
    {
        return $this;
    }

    /*
    protected static function boot()
    {
        parent::boot();
        static::creating(function(Currenttransformerinfo $model){
            dd($model);
        });
    }
    */

}
