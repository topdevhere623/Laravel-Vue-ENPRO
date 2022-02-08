<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $code_group
 * @property string $title
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property EnproDefect[] $enproDefects
 */
class EnproGroupDefect extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enpro_group_defect';

    const title1 = 'Группы дефектов';
    const title2 = 'Группы дефектов';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['code_group', 'title'];

    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    /**
     * @return HasMany
     */
    public function enproDefects()
    {
        return $this->hasMany('App\Models\EnproDefect', 'group_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereRaw("upper(concat(code_group, title)) like upper('%$search%')");
    }
}
