<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\OrganisationRoleTrait;
use App\Contracts\CIM\AssetInfo\OrganisationRoleInterface;

/**
 * Class OrganisationRole
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\Organisation $Organisation
 * @property \App\Models\IdentifiedObject $IdentifiedObject
 * @property \App\Models\Name[] $Names

 */
class OrganisationRole extends BaseModel implements OrganisationRoleInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use OrganisationRoleTrait;
    public $parentIdentifiedObject = null;

    // управляемая таблица
    public $table = 'organisation_role';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'organisation_id',
      'identifiedobject_id',

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
        'organisation_id' => 'integer',
        'identifiedobject_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Organisation()
    {
        return $this->belongsTo(\App\Models\Organisation::class, 'organisation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function IdentifiedObject()
    {
        return $this->belongsTo(\App\Models\IdentifiedObject::class, 'identifiedobject_id');
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

        static::creating(function (OrganisationRole $model) {
            $IdentifiedObject = $model->getIdentifiedObject();
            if (! empty($IdentifiedObject)) {
                $IdentifiedObject->save();
                $model->IdentifiedObject()->associate($IdentifiedObject);
            };

            
            
        });

    }


    public function getOrganisationRole() : OrganisationRole
    {
        return $this;
    }
}
