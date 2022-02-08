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
 * Class StreetDetail
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property int $id
 * @property string $address_general
 * @property string $address_general2
 * @property string $address_general3
 * @property string $building_name
 * @property string $code
 * @property string $name
 * @property string $number
 * @property string $prefix
 * @property string $suffix
 * @property string $suite_number
 * @property string $type
 * @property boolean $within_town_limits

 */
class StreetDetail extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;



    // управляемая таблица
    public $table = 'street_detail';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'address_general',
      'address_general2',
      'address_general3',
      'building_name',
      'code',
      'name',
      'number',
      'prefix',
      'suffix',
      'suite_number',
      'type',
      'within_town_limits',

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
        'address_general' => 'string',
        'address_general2' => 'string',
        'address_general3' => 'string',
        'building_name' => 'string',
        'code' => 'string',
        'name' => 'string',
        'number' => 'string',
        'prefix' => 'string',
        'suffix' => 'string',
        'suite_number' => 'string',
        'type' => 'string',
        'within_town_limits' => 'boolean',

    ];

    public function getStreetDetail() : StreetDetail
    {
        return $this;
    }
}
