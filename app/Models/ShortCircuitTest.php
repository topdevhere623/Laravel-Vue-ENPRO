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
use App\Traits\ShortCircuitTestTrait;
use App\Contracts\CIM\OldTransformerEndInfo\ShortCircuitTestInterface;

/**
 * Class ShortCircuitTest
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\TransformerEndInfo $EnergisedEnd
 * @property \App\Models\KiloActivePower $loss
 * @property \App\Models\PerCent $voltage
 * @property \App\Models\TransformerTest $TransformerTest
 * @property \App\Models\TransformerEndInfo[] $GroundedEnds

 */
class ShortCircuitTest extends BaseModel implements ShortCircuitTestInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use ShortCircuitTestTrait;

    // управляемая таблица
    public $table = 'short_circuit_test';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'energised_end_id',
      'loss_id',
      'voltage_id',
      'transformer_test_id',

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
        'energised_end_id' => 'integer',
        'loss_id' => 'integer',
        'voltage_id' => 'integer',
        'transformer_test_id' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function EnergisedEnd()
    {
        return $this->belongsTo(\App\Models\TransformerEndInfo::class, 'energised_end_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loss()
    {
        return $this->belongsTo(\App\Models\KiloActivePower::class, 'loss_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voltage()
    {
        return $this->belongsTo(\App\Models\PerCent::class, 'voltage_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function TransformerTest()
    {
        return $this->belongsTo(\App\Models\TransformerTest::class, 'transformer_test_id');
    }

    /**
     * @return BelongsToMany|Builder
     */
    public function GroundedEnds(): BelongsToMany
    {
        return $this->belongsToMany(TransformerEndInfo::class, 'pivot_short_circuit_test_transformer_end_info', 'short_circuit_test_id', 'transformer_end_info_id');
    }

    public function getShortCircuitTest() : ShortCircuitTest
    {
        return $this;
    }
}
