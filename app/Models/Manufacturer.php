<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ManufacturerTrait;
use App\Contracts\CIM\AssetInfo\ManufacturerInterface;

/**
 * Class Manufacturer
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\OrganisationRole $OrganisationRole

 */
class Manufacturer extends BaseModel implements ManufacturerInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use ManufacturerTrait;
    public $parentOrganisationRole = null;

    // управляемая таблица
    public $table = 'manufacturer';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'organisation_role_id',

    ];

    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Производитель";
    const title2 = "Производители";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/manufacturer/' . $this->id;
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'organisation_role_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OrganisationRole()
    {
        return $this->belongsTo(\App\Models\OrganisationRole::class, 'organisation_role_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (Manufacturer $model) {
            $OrganisationRole = $model->getOrganisationRole();
            if (! empty($OrganisationRole)) {
                $OrganisationRole->save();
                $model->OrganisationRole()->associate($OrganisationRole);
            };



        });

    }


    public function getManufacturer() : Manufacturer
    {
        return $this;
    }
}
