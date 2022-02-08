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
use App\Traits\TransformerTestTrait;
use App\Contracts\CIM\OldTransformerEndInfo\TransformerTestInterface;

/**
 * Class TransformerTest
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\Temperature $temperature
 * @property \App\Models\IdentifiedObject $IdentifiedObject
 * @property \App\Models\Name[] $Names

 */
class TransformerTest extends BaseModel implements TransformerTestInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use TransformerTestTrait;

    // управляемая таблица
    public $table = 'transformer_test';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'temperature_id',
      'identified_object_id',

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
        'temperature_id' => 'integer',
        'identified_object_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function temperature()
    {
        return $this->belongsTo(\App\Models\Temperature::class, 'temperature_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function IdentifiedObject()
    {
        return $this->belongsTo(\App\Models\IdentifiedObject::class, 'identified_object_id');
    }

    /**
     * @return array
     */
    public function Names(): array
    {
        return $this->IdentifiedObject->Names;
    }



    public function getTransformerTest() : TransformerTest
    {
        return $this;
    }
}
