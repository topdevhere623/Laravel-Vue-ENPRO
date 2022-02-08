<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataTypeTrait;



/**
 * Class Mass
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property float $value

 */
class Mass extends BaseModel
{
    // использование мягкого удаления
    use DataTypeTrait;



    // управляемая таблица
    public $table = 'mass';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'multiplier_id',
      'unit_id',
      'value',

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
        'multiplier_id' => 'integer',
        'unit_id' => 'integer',
        'value' => 'float',

    ];




    public function getMass() : Mass
    {
        return $this;
    }
}
