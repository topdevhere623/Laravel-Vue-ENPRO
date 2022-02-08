<?php

namespace App\Models;

use Czim\NestedModelUpdater\Traits\NestedUpdatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnumKindModel extends BaseModel
{
    use SoftDeletes;
    use NestedUpdatable;
    // список полей запрещенных на редактирование
    public $fillable = [
        'value',
        'description',
        'ru_value',
        'enpro_code',
        'literal',

    ];
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
        'value' => 'integer',
        'description' => 'string',
        'ru_value' => 'string',
        'enpro_code' => 'string',
        'literal' => 'string',

    ];
}
