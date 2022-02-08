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
use App\Traits\ProcedureTrait;
use App\Contracts\CIM\Procedure\ProcedureInterface;

/**
 * Class Procedure
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\Document $Document
 * @property \App\Models\EnproDefect[] $measurements
 * @property string $sequenceNumber
 * @property string $status

 */
class Procedure extends BaseModel implements ProcedureInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use NestedUpdatable;
    use ProcedureTrait;

    // управляемая таблица
    public $table = 'procedure';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'document_id',
      'sequenceNumber',
      'status',

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
        'document_id' => 'integer',
        'sequenceNumber' => 'string',
        'status' => 'string',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Document()
    {
        return $this->belongsTo(\App\Models\Document::class, 'document_id');
    }

    /**
     * @return BelongsToMany|Builder
     */
    public function measurements(): BelongsToMany
    {
        return $this->belongsToMany(EnproDefect::class, 'pivot_procedure_enpro_defect', 'procedure_id', 'enpro_defect_id');
    }



    public function getProcedure() : Procedure
    {
        return $this;
    }
}
