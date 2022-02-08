<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property float $denominator
 * @property float $numerator
 * @property Currenttransformerinfo[] $currenttransformerinfo
 */
class Ratio extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ratio';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['denominator', 'numerator'];

    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    /**
     * @return HasMany
     */
    public function currenttransformerinfo()
    {
        return $this->hasMany('App\Models\Currenttransformerinfo', 'nominal_ratio_id');
    }
}
