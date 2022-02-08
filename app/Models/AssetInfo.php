<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\AssetInfoTrait;
use App\Contracts\CIM\AssetInfo\AssetInfoInterface;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;

/**
 * Class AssetInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\ProductAssetModel $ProductAssetModel
 * @property \App\Models\IdentifiedObject $IdentifiedObject
 * @property \App\Models\Name[] $Names
 * @property \App\Models\CatalogAssetType $CatalogAssetType

 */
class AssetInfo extends BaseModel implements AssetInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use AssetInfoTrait;
    use NestedUpdatable;
    public $parentIdentifiedObject = null;

    // управляемая таблица
    public $table = 'asset_info';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'product_asset_model_id',
      'identifiedobject_id',

    ];

    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "";
    const title2 = "";
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_asset_model_id' => 'integer',
        'identifiedobject_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductAssetModel()
    {
        return $this->belongsTo(\App\Models\ProductAssetModel::class, 'product_asset_model_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function IdentifiedObject()
    {
        return $this->belongsTo(\App\Models\Identifiedobject::class, 'identifiedobject_id');
    }

    /**
     * @return array
     */
    public function Names(): array
    {
        return $this->IdentifiedObject->Names;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CatalogAssetType()
    {
        return $this->belongsTo(\App\Models\CatalogAssetType::class, 'catalog_asset_type_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (AssetInfo $model) {
            $IdentifiedObject = $model->getIdentifiedObject();
            if (! empty($IdentifiedObject)) {
                $IdentifiedObject->save();
                $model->IdentifiedObject()->associate($IdentifiedObject);
            };



        });

    }


    public function getAssetInfo() : AssetInfo
    {
        return $this;
    }

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/assetinfo/' . $this->id;
    }
}
