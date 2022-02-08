<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\AssetContainerTrait;
use App\Contracts\CIM\Asset\AssetGroupInterface;
use App\Traits\AssetGroupTrait;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;

/**
 * Class AssetGroup
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property int $id
 * @property \App\Models\Document $Document
 * @property \App\Models\AssetGroupKind $AssetGroupKind
 * @property \App\Models\Asset[] $Assets

 */
class AssetGroup extends BaseModel implements AssetGroupInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use AssetGroupTrait;
    use NestedUpdatable;

    public $parentDocument = null;

    // управляемая таблица
    public $table = 'asset_group';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'document_id',
      'asset_group_kind_id',

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
        'document_id' => 'integer',
        'asset_group_kind_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function AssetGroupKind()
    {
        return $this->belongsTo(\App\Models\AssetGroupKind::class, 'asset_group_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Document()
    {
        return $this->belongsTo(\App\Models\Document::class, 'document_id');
    }

    /**
     * @return BelongsToMany|Builder
     */
    public function Assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'pivot_asset_container_asset', 'asset_container_id', 'asset_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function (AssetGroup $model) {
            $Document = $model->getDocument();
            if (! empty($Document)) {
                $Document->save();
                $model->Document()->associate($Document);
            };
        });

    }


    public function getAssetGroup() : AssetGroup
    {
        return $this;
    }
}
