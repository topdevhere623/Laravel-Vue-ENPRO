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

/**
 * Class AssetGroupKind
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property int $id
 * @property string $value
 * @property string $description

 */
class AssetGroupKind extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;


    // управляемая таблица
    public $table = 'asset_group_kind';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'value',
      'description',

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
        'value' => 'string',
        'description' => 'string',

    ];

}
