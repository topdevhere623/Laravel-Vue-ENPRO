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
 * Class TownDetail
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property int $id
 * @property string $code
 * @property string $country
 * @property string $name
 * @property string $section
 * @property string $state_or_province

 */
class TownDetail extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;



    // управляемая таблица
    public $table = 'town_detail';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'code',
      'country',
      'name',
      'section',
      'state_or_province',

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
        'code' => 'string',
        'country' => 'string',
        'name' => 'string',
        'section' => 'string',
        'state_or_province' => 'string',

    ];

    public function getTownDetail() : TownDetail
    {
        return $this;
    }
}
