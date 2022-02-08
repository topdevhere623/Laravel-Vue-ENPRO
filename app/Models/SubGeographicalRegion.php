<?php

namespace App\Models;

use App\Contracts\CIM\Wires\SubGeographicalRegionInterface;
use App\Traits\IdentifiedObjectParentTrait;
use App\Traits\IdentifiedObjectTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubGeographicalRegion extends Model implements SubGeographicalRegionInterface
{
    use IdentifiedObjectTrait;
    use IdentifiedObjectParentTrait;

    public $region = null;

    public static function boot()
    {
        parent::boot();
        self::creating(function(Model $model){
            $model->getIdentifiedObject()->save();
            $model->setIdentifiedObject($model->getIdentifiedObject());
        });
        self::saving(function(Model $model){
            $model->getIdentifiedObject()->save();
            if($model->getRegion()) {
                $model->getRegion()->save();
                $model->region()->associate($model->getRegion());
            } else $model->region()->dissociate();
        });
    }

    public function region() : BelongsTo
    {
       return  $this->belongsTo(GeographicalRegion::class, 'region');
    }

    //
    public function getRegion(): ?GeographicalRegion
    {
        if($this->region) return $this->region;
        else if($this->region()->get()->get(0)) {
            $this->region = $this->region()->get()->get(0);
        }
        return $this->region;
    }

    public function setRegion(GeographicalRegion $region): void
    {
        $this->region = $region;
    }

    public function removeRegion(): void
    {
        if($this->region->id) {
            $this->region()->dissociate();
        }
        $this->region = null;
    }
}
