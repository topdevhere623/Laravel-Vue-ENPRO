<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TapeShieldCableInfoTrait;
use App\Contracts\CIM\AssetInfo\TapeShieldCableInfoInterface;

/**
 * Class TapeShieldCableInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\CableInfo $CableInfo

 */
class TapeShieldCableInfo extends BaseModel implements TapeShieldCableInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use TapeShieldCableInfoTrait;
    public $parentCableInfo = null;

    // управляемая таблица
    public $table = 'tape_shield_cable_info';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'cable_info_id',

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
        'cable_info_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CableInfo()
    {
        return $this->belongsTo(\App\Models\CableInfo::class, 'cable_info_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (TapeShieldCableInfo $model) {
            $CableInfo = $model->getCableInfo();
            if (! empty($CableInfo)) {
                $CableInfo->save();
                $model->CableInfo()->associate($CableInfo);
            };

            
            
        });

    }


    public function getTapeShieldCableInfo() : TapeShieldCableInfo
    {
        return $this;
    }
}
