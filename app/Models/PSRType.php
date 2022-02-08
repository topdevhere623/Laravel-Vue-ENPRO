<?php

namespace App\Models;

use App\Contracts\CIM\Wires\PSRTypeInterface;
use App\Traits\IdentifiedObjectParentTrait;
use App\Traits\IdentifiedObjectTrait;
use Illuminate\Database\Eloquent\Model;

class PSRType extends Model implements PSRTypeInterface
{
    use IdentifiedObjectTrait;
    use IdentifiedObjectParentTrait;

    public static function boot()
    {
        parent::boot();
        self::creating(function(PSRType $model){
            $model->getIdentifiedObject()->save();
            $model->setIdentifiedObject($model->getIdentifiedObject());
        });
        self::saving(function(PSRType $model){
            $model->getIdentifiedObject()->save();
        });
    }
}
