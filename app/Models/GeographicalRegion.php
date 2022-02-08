<?php

namespace App\Models;

use App\Contracts\CIM\Wires\GeographicalRegionInterface;
use App\Traits\IdentifiedObjectParentTrait;
use App\Traits\IdentifiedObjectTrait;
use Illuminate\Database\Eloquent\Model;

class GeographicalRegion extends Model implements GeographicalRegionInterface
{
    use IdentifiedObjectTrait;
    use IdentifiedObjectParentTrait;

    public static function boot()
    {
        parent::boot();
        self::creating(function(GeographicalRegion $model){
            $model->getIdentifiedObject()->save();
            $model->setIdentifiedObject($model->getIdentifiedObject());
        });
        self::saving(function(GeographicalRegion $model){
            $model->getIdentifiedObject()->save();
        });
    }
    //
}
