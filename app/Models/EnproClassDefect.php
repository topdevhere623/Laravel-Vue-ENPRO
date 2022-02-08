<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @property integer $id
 * @property string $type
 * @property string $class
 * @property string $title
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property EnproDefect[] $enproDefects
 */
class EnproClassDefect extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enpro_class_defect';

    const title1 = 'Группы оборудования';
    const title2 = 'Группы оборудования';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['type', 'class', 'title'];

    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    /**
     * @return HasMany
     */
    public function enproDefects()
    {
        return $this->hasMany('App\Models\EnproDefect', 'class_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query
            ->whereRaw("upper(concat(type, class, title)) like upper('%$search%')");
    }
}
