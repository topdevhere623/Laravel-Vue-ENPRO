<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $class_id
 * @property integer $group_id
 * @property string $code
 * @property string $title
 * @property int $critical
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property EnproClassDefect $enproClassDefect
 * @property EnproGroupDefect $enproGroupDefect
 */
class EnproDefect extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enpro_defect';

    const title1 = 'Дефекты';
    const title2 = 'Дефекты';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['class_id', 'group_id', 'code', 'title', 'critical'];

    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    /**
     * @return BelongsTo
     */
    public function enproClassDefect()
    {
        return $this->belongsTo('App\Models\EnproClassDefect', 'class_id');
    }

    /**
     * @return BelongsTo
     */
    public function enproGroupDefect()
    {
        return $this->belongsTo('App\Models\EnproGroupDefect', 'group_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query
            ->whereRaw("upper(concat(code, title)) like upper('%$search%')")
            ->orWhereRaw("class_id in(select id from enpro_class_defect where upper(concat(type, class, title)) like upper('%$search%'))")
            ->orWhereRaw("group_id in(select id from enpro_group_defect where upper(concat(code_group, title)) like upper('%$search%'))")
            ;
    }

}
