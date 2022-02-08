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
 * Class CableOuterJacketKind
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property string $value
 * @property string $description
 * @property string $enpro_code

 */
class CableOuterJacketKind extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;

    const title1 = 'Материал наружной оболочки';
    const title2 = 'Материалы наружной оболочки';

    // управляемая таблица
    public $table = 'cable_outer_jacket_kind';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'value',
      'ru_value',
      'description',
      'enpro_code',

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
        'ru_value' => 'string',
        'description' => 'string',
        'enpro_code' => 'string',

    ];


    public function getCableOuterJacketKind() : CableOuterJacketKind
    {
        return $this;
    }
}
