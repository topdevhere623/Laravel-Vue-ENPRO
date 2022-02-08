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
use Czim\NestedModelUpdater\Traits\NestedUpdatable;

/**
 * Class CatalogAssetType
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\Identifiedobject $IdentifiedObject

 */
class CatalogAssetType extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;

    // управляемая таблица
    public $table = 'catalog_asset_type';

    // мои атрибуты модели
    const title1 = "Марка оборудования";
    const title2 = "Марки оборудования";

    // список полей, разрешенных на редактирование
    public $fillable = [
      'identified_object_id',
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
        'identified_object_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function IdentifiedObject()
    {
        return $this->belongsTo(\App\Models\Identifiedobject::class, 'identified_object_id');
    }

    public function getCatalogAssetType() : CatalogAssetType
    {
        return $this;
    }
}
