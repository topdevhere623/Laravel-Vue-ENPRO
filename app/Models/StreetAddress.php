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
 * Class StreetAddress
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property int $id
 * @property \App\Models\StreetDetail $streetDetail
 * @property \App\Models\TownDetail $townDetail
 * @property string $po_box
 * @property string $postal_code

 */
class StreetAddress extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;



    // управляемая таблица
    public $table = 'street_address';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'street_detail_id',
      'town_detail_id',
      'po_box',
      'postal_code',

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
        'street_detail_id' => 'string',
        'town_detail_id' => 'string',
        'po_box' => 'string',
        'postal_code' => 'string',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function streetDetail()
    {
        return $this->belongsTo(\App\Models\StreetDetail::class, 'street_detail_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function townDetail()
    {
        return $this->belongsTo(\App\Models\TownDetail::class, 'town_detail_id');
    }

    public function getStreetAddress() : StreetAddress
    {
        return $this;
    }
}
