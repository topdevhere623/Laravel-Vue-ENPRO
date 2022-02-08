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
use App\Traits\NoLoadTestTrait;
use App\Contracts\CIM\OldTransformerEndInfo\NoLoadTestInterface;

/**
 * Class NoLoadTest
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\TransformerEndInfo $EnergisedEnd
 * @property \App\Models\KiloActivePower $loss
 * @property \App\Models\PerCent $excitingCurrent
 * @property \App\Models\TransformerTest $TransformerTest

 */
class NoLoadTest extends BaseModel implements NoLoadTestInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use NoLoadTestTrait;

    // управляемая таблица
    public $table = 'no_load_test';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'energised_end_id',
      'loss_id',
      'exciting_current_id',
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
        'exciting_current_id' => 'integer',
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
    public function excitingCurrent()
    {
        return $this->belongsTo(\App\Models\PerCent::class, 'exciting_current_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function TransformerTest()
    {
        return $this->belongsTo(\App\Models\TransformerTest::class, 'transformer_test_id');
    }



    public function getNoLoadTest() : NoLoadTest
    {
        return $this;
    }
}
