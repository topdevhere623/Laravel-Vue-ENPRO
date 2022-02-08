<?php

namespace App\Models;

use App\Models\BaseModel as BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\AssetTrait;
use App\Contracts\CIM\Asset\AssetInterface as AssetInterface;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;

// трайты
use App\Traits\CommonTrait;

// модели

/**
 * @property integer $id
 * @property integer $gost_id
 * @property integer $manufacturer_id
 * @property integer $powersystemresources_id
 * @property string $keylink
 * @property float $initiallossoflife
 * @property string $corporatecode
 * @property string $installationdate
 * @property string $manufactureddate
 * @property string $serialnumber
 * @property string $inventorynumber
 * @property int $initialcondition
 * @property string $purchasedate
 * @property float $purchaseprice
 * @property string $receiveddate
 * @property string $retireddate
 * @property string $orgmanagerkey
 * @property string $fgc_parentkey
 * @property string $orgassetownerkey
 * @property string $type
 * @property string $assetinfokey
 * @property string $manufactureddt
 * @property string $assetcol
 * @property string $deliverydate
 * @property int $ownereqassetid
 * @property string $comment
 * @property int $critical
 * @property string $cadastralnumber
 * @property string $manufacturer
 * @property int $warehouse
 * @property string $inventorynumbermp
 * @property string $inventorynumberbp
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Gost $gost
 * @property Manufacturer $manufacture
 * @property PowerSystemResource $powerSystemResource
 * @property Acline[] $aclines
 * @property Breaker[] $breakers
 * @property Busbarsection[] $busbarsections
 * @property Circuitbreaker[] $circuitbreakers
 * @property Connector[] $connectors
 * @property Currenttransformer $currenttransformer
 * @property Discharger[] $dischargers
 * @property Disconnector[] $disconnectors
 * @property Disconnectorfuse[] $disconnectorfuses
 * @property Endpoint[] $endpoints
 * @property Fuse[] $fuses
 * @property Grounddisconnector[] $grounddisconnectors
 * @property Identifiedobject[] $identifiedobjects
 * @property Loadbreakswitch[] $loadbreakswitches
 * @property Maintenance[] $maintenances
 * @property Meastest[] $meastests
 * @property Powertransformer[] $powertransformers
 * @property Substation[] $substations
 * @property Voltagetransformer[] $voltagetransformers
 * @property \App\Models\Identifiedobject $IdentifiedObject
 * @property \App\Models\Location $Location
 * @property \App\Models\Status $status
 * @property \App\Models\PowerSystemResource[] $PowerSystemResources
 * @property \App\Models\Name[] $Names
 * @property string $utc_number
 * @property string $lot_number
 * @property string $electronic_address
 * @property int $enpro_class_defect_id
 * @property \App\Models\EnproClassDefect $enproClassDefect
 * @property \App\Models\AssetGroup[] $AssetGroups
 * @property \App\Models\AssetInfo $AssetInfo
 * @property \App\Models\AssetKind $kind
 */
// модель
class Asset extends BaseModel implements AssetInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use AssetTrait;
    use NestedUpdatable;
    public $parentIdentifiedObject = null;
    public $location = null;
    // управляемая таблица
    protected $table = "asset";

    /**
     * список полей, разрешенных на редактирование
     * @var array
     */
    protected $fillable = [
        'gost_id',
        'manufacturer_id',
        'powersystemresources_id',
        'keylink',
        'initiallossoflife',
        'corporatecode',
        'installationdate',
        'manufactureddate',
        'serialnumber',
        'inventorynumber',
        'initialcondition',
        'purchasedate',
        'purchaseprice',
        'receiveddate',
        'retireddate',
        'orgmanagerkey',
        'fgc_parentkey',
        'orgassetownerkey',
        'type',
        'assetinfokey',
        'manufactureddt',
        'assetcol',
        'deliverydate',
        'ownereqassetid',
        'comment',
        'critical',
        'cadastralnumber',
        'manufacturer',
        'warehouse',
        'inventorynumbermp',
        'inventorynumberbp',
        'identifiedobject_id',
        'location_id',
        'status_id',
        'utc_number',
        'lot_number',
        'electronic_address',
        'asset_info_id',
        'enpro_class_defect_id',
        'asset_kind_id',
    ];

    /**
     * Date Fields format date and time
     * @var string[]
     */
    protected $casts = [
        'installationdate' => 'datetime:Y-m-d H:i:s',
        'manufactureddate' => 'datetime:Y-m-d H:i:s',
        'purchasedate' => 'datetime:Y-m-d H:i:s',
        'receiveddate' => 'datetime:Y-m-d H:i:s',
        'retireddate' => 'datetime:Y-m-d H:i:s',
        'deliverydate' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'id' => 'integer',
        'identifiedobject_id' => 'integer',
        'location_id' => 'integer',
        'status_id' => 'string',
        'utcnumber' => 'string',
        'serialnumber' => 'string',
        'lot_number' => 'string',
        'purchaseprice' => 'float',
        'electronic_address' => 'string',
        'initialcondition' => 'string',
        'type' => 'string',
        'critical' => 'boolean',
        'asset_info_id' => 'integer',
        'enpro_class_defect_id' => 'integer',
        'asset_kind_id' => 'integer',
    ];

    /**
     * список полей запрещенных на редактирование
     * @var array
     */
    protected $guarded = [];

    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Общие данные";
    const title2 = "Общие данные";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/asset/' . $this->id;
    }

    // связи

    /**
     * @return BelongsTo
     */
    public function gost()
    {
        return $this->belongsTo('App\Models\Gost');
    }

    /**
     * @return BelongsTo
     */
    public function manufacture()
    {
        return $this->belongsTo('App\Models\Manufacturer');
    }

    /**
     * @return BelongsTo
     */
    public function powerSystemResource()
    {
        return $this->belongsTo('App\Models\PowerSystemResource', 'powersystemresources_id');
    }

    /**
     * @return HasMany
     */
    public function aclines()
    {
        return $this->hasMany('App\Models\Acline');
    }

    /**
     * @return HasMany
     */
    public function breakers()
    {
        return $this->hasMany('App\Models\Breaker');
    }

    /**
     * @return HasMany
     */
    public function busbarsections()
    {
        return $this->hasMany('App\Models\Busbarsection');
    }

    /**
     * @return HasMany
     */
    public function circuitbreakers()
    {
        return $this->hasMany('App\Models\Circuitbreaker');
    }

    /**
     * @return HasMany
     */
    public function connectors()
    {
        return $this->hasMany('App\Models\Connector');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currenttransformer()
    {
        return $this->hasOne('App\Models\Currenttransformer', 'asset_id');
    }

    /**
     * @return HasMany
     */
    public function dischargers()
    {
        return $this->hasMany('App\Models\Discharger');
    }

    /**
     * @return HasMany
     */
    public function disconnectors()
    {
        return $this->hasMany('App\Models\Disconnector');
    }

    /**
     * @return HasMany
     */
    public function disconnectorfuses()
    {
        return $this->hasMany('App\Models\Disconnectorfuse');
    }

    /**
     * @return HasMany
     */
    public function endpoints()
    {
        return $this->hasMany('App\Models\Endpoint');
    }

    /**
     * @return HasMany
     */
    public function fuses()
    {
        return $this->hasMany('App\Models\Fuse');
    }

    /**
     * @return HasMany
     */
    public function grounddisconnectors()
    {
        return $this->hasMany('App\Models\Grounddisconnector');
    }

    /**
     * @return HasMany
     */
    public function identifiedobjects()
    {
        return $this->hasMany('App\Models\Identifiedobject');
    }

    /**
     * @return HasMany
     */
    public function loadbreakswitches()
    {
        return $this->hasMany('App\Models\Loadbreakswitch');
    }

    /**
     * @return HasMany
     */
    public function maintenances()
    {
        return $this->hasMany('App\Models\Maintenance');
    }

    /**
     * @return HasMany
     */
    public function meastests()
    {
        return $this->hasMany('App\Models\Meastest');
    }

    /**
     * @return HasMany
     */
    public function powertransformers()
    {
        return $this->hasMany('App\Models\Powertransformer');
    }

    /**
     * @return HasMany
     */
    public function substations()
    {
        return $this->hasMany('App\Models\Substation');
    }

    /**
     * @return HasMany
     */
    public function voltagetransformers()
    {
        return $this->hasMany('App\Models\Voltagetransformer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function IdentifiedObject()
    {
        return $this->belongsTo(\App\Models\Identifiedobject::class, 'identifiedobject_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_id');
    }

    /**
     * @return BelongsToMany|Builder
     */
    public function PowerSystemResources(): BelongsToMany
    {
        return $this->belongsToMany(PowerSystemResource::class, 'pivot_asset_power_system_resources', 'asset_id', 'power_system_resources_id');
    }

    /**
     * @return array
     */
    public function Names(): array
    {
        return $this->IdentifiedObject->Names;
    }

    /**
     * @return BelongsToMany|Builder
     */
    public function Assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_container', 'asset_container_id', 'asset_id');
    }

    /**
     * @return BelongsToMany|Builder
     */
    public function AssetGroups(): BelongsToMany
    {
        return $this->belongsToMany(AssetGroup::class, 'pivot_asset_asset_group', 'asset_id', 'asset_group_id');
    }

    /**
     * @return array
     */
    public function getAssetGroupIds(): array
    {
        return (empty($this->AssetGroups)) ? [] : $this->AssetGroups()->pluck('id')->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function AssetInfo()
    {
        return $this->belongsTo(\App\Models\AssetInfo::class, 'assetinfo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kind()
    {
        return $this->belongsTo(\App\Models\AssetKind::class, 'asset_kind_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproClassDefect() : BelongsTo
    {
        return $this->belongsTo(EnproClassDefect::class, 'enpro_class_defect_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function (Asset $model) {
            $model->PowerSystemResources()->saveMany($model->getPowerSystemResources());
        });
    }

    public function getAsset() : Asset
    {
        return $this;
    }



}
