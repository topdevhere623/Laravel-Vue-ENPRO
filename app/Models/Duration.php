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
 * Class Duration
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property string $value

 */
class Duration extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;


    // управляемая таблица
    public $table = 'duration';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'value',

    ];

    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Продолжительность";
    const title2 = "Продолжительности";
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function setValueAttribute($value)
    {
        $durationParts = [];
        if (! empty($value['years'])) {
            $durationParts[] = $value['years'] . 'Y';
        }
        $this->attributes['value'] = empty($durationParts) ? null : 'P'. implode('', $durationParts);
    }

    public function getValueAttribute($value)
    {
        if (empty($value)) return null;
        preg_match('~P(.*?)Y~', $value, $output);
        return ['years' => $output[1]];

    }

    public function getDuration() : Duration
    {
        return $this;
    }
}
