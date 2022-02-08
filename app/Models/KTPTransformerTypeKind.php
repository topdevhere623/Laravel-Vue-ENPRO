<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;



/**
 * Class KTPTransformerTypeKind
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property string $value
 * @property string $description
 * @property string $ru_value
 * @property string $enpro_code

 */
class KTPTransformerTypeKind extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;

    const title1 = "Вид типа силового трансформатора";
    const title2 = "Виды типа силового трансформатора";
    // управляемая таблица
    public $table = 'k_t_p_transformer_type_kind';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'value',
      'description',
      'ru_value',
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
        'description' => 'string',
        'ru_value' => 'string',
        'enpro_code' => 'string',

    ];



    public function getKTPTransformerTypeKind() : KTPTransformerTypeKind
    {
        return $this;
    }
}
