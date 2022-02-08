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
use App\Contracts\CIM\Asset\AssetContainerInterface;

/**
 * Class AssetContainer
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property int $id
 * @property \App\Models\Asset $Asset
 * @property \App\Models\Asset[] $Assets

 */
class AssetContainer extends BaseModel implements AssetContainerInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use AssetContainerTrait;
    public $parentAsset = null;

    // управляемая таблица
    public $table = 'asset_container';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'asset_id',

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
        'asset_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Asset()
    {
        return $this->belongsTo(\App\Models\Asset::class, 'asset_id');
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
        static::created(function (AssetContainer $model) {
            $model->Assets()->saveMany($model->getAssets());
        });

        static::creating(function (AssetContainer $model) {
            $Asset = $model->getAsset();
            if (! empty($Asset)) {
                $Asset->save();
                $model->Asset()->associate($Asset);
            };
        });

    }


    public function getAssetContainer() : AssetContainer
    {
        return $this;
    }
}
