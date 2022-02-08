<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LocationTrait;
use App\Contracts\CIM\Asset\LocationInterface;

/**
 * Class Location
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property int $id
 * @property \App\Models\IdentifiedObject $IdentifiedObject
 * @property \App\Models\CoordinateSystem $CoordinateSystem
 * @property \App\Models\StreetAddress $main_address
 * @property \App\Models\Name[] $Names
 * @property string $direction

 */
class Location extends BaseModel implements LocationInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use LocationTrait;
    public $parentIdentifiedObject = null;

    // управляемая таблица
    public $table = 'location';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'identified_object_id',
      'coordinate_system_id',
      'main_address_id',
      'direction',

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
        'identified_object_id' => 'string',
        'coordinate_system_id' => 'string',
        'main_address_id' => 'string',
        'direction' => 'string',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function IdentifiedObject()
    {
        return $this->belongsTo(\App\Models\IdentifiedObject::class, 'identified_object_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CoordinateSystem()
    {
        return $this->belongsTo(\App\Models\CoordinateSystem::class, 'coordinate_system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mainAddress()
    {
        return $this->belongsTo(\App\Models\StreetAddress::class, 'main_address_id');
    }

    /**
     * @return array
     */
    public function Names(): array
    {
        return $this->IdentifiedObject->Names;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (Location $model) {
            $IdentifiedObject = $model->getIdentifiedObject();
            if (! empty($IdentifiedObject)) {
                $IdentifiedObject->save();
                $model->IdentifiedObject()->associate($IdentifiedObject);
            };

        });
    }

    public function getLocation() : Location
    {
        return $this;
    }
}
