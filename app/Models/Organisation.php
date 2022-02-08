<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;



/**
 * Class Organisation
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\StreetAddress $postalAddress
 * @property \App\Models\StreetAddress $streetAddress
 * @property string $electronicAddress
 * @property string $phone1
 * @property string $phone2

 */
class Organisation extends BaseModel 
{
    // использование мягкого удаления
    use SoftDeletes;
    
    

    // управляемая таблица
    public $table = 'organisation';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'postal_address_id',
      'street_address_id',
      'electronicAddress',
      'phone1',
      'phone2',

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
        'postal_address_id' => 'integer',
        'street_address_id' => 'integer',
        'electronicAddress' => 'string',
        'phone1' => 'string',
        'phone2' => 'string',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postalAddress()
    {
        return $this->belongsTo(\App\Models\StreetAddress::class, 'postal_address_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function streetAddress()
    {
        return $this->belongsTo(\App\Models\StreetAddress::class, 'street_address_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (Organisation $model) {

            
            
        });

    }


    public function getOrganisation() : Organisation
    {
        return $this;
    }
}
