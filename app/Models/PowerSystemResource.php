<?php

namespace App\Models;

use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Contracts\CIM\Wires\PowerTransformerInterface;
use App\Traits\IdentifiedObjectTrait;
use App\Traits\PowerSystemResourceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\PowerSystemResource
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $psrtype_id
 * @property int $identifiedobject_id
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource query()
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource whereIdentifiedobjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource wherePsrtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerSystemResource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PowerSystemResource extends Model implements PowerSystemResourceInterface
{
    public $io;
    public $psrtype = null;
    use PowerSystemResourceTrait;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (PowerSystemResource  $model) {
            if($model->getIdentifiedObject()) {
                $model->getIdentifiedObject()->save();
                $model->identifiedobjectBelong()->associate($model->getIdentifiedObject());
            };
            if($model->getPSRType()) {
                $model->getPSRType()->save();
                $model->psrtype()->associate($model->getPSRType());
            }
        });

        static::deleted(function (PowerSystemResource $model) {
            $model->getIdentifiedObject()->delete();
            $model->assets()->delete();
            $model->psrtype()->delete();
        });

        static::saving(function (PowerSystemResource $model) {
            $model->getIdentifiedObject()->save();
            if($model->getPSRType()) {
                $model->getPSRType()->save();
                $model->getPSRType()->selfIdentification();
                $model->psrtype()->associate($model->getPSRType());
            }
        });
    }


    public function getPowerSystemResource()
    {
        return $this;
    }
}
