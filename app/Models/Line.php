<?php

namespace App\Models;

use App\Contracts\CIM\Wires\LineInterface;
use App\Traits\EquipmentContainerTrait;
use App\Traits\PowerSystemResourceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Line extends Model implements LineInterface
{
    protected $table = 'line';

    use EquipmentContainerTrait;


    static function boot()
    {
        parent::boot();
        self::creating(function (Line $model) {
            $model->getEquipmentContainer()->save();
            $model->equipmentContainer()->associate($model->getEquipmentContainer());
        });
        self::created(function (Line $model) {
            if($model->id && !$model->getIdentification()) {
                $model->selfIdentification();
                $model->getMRID();
                $model->save();
            }
        });
        self::saving(function(Line $model){
            $model->getEquipmentContainer()->save();
            $model->equipmentContainer()->associate($model->getEquipmentContainer());
            if($model->getRegion()) {
                $model->getRegion()->save();
                $model->region()->associate($model->getRegion());
            } else {
                $model->region()->dissociate();
            }
        });
    }

    public $region = null;

    public $equipmentContainer = null;

    public function equipmentContainer() : BelongsTo
    {
        return $this->getLine()->belongsTo(EquipmentContainer::class, 'equipment_containers_id');
    }

    public function getEquipmentContainer() : EquipmentContainer
    {
        if($this->equipmentContainer) return $this->equipmentContainer;
        if($this->equipmentContainer()->get()->get(0)) {
            $this->equipmentContainer = $this->equipmentContainer()->get()->get(0);
        } else $this->equipmentContainer = new EquipmentContainer();
        return $this->equipmentContainer;
    }

    public function getLine() : Line
    {
        return $this;
    }
    public function region():BelongsTo
    {
        return $this->getLine()->belongsTo(SubGeographicalRegion::class, 'sub_geographical_regions_id');
    }


    //
    public function getRegion(): ?SubGeographicalRegion
    {
        if($this->getLine()->region) return $this->getLine()->region;
        if($this->region()->get()->get(0)) {
            $this->getLine()->region =  $this->region()->get()->get(0);
        }
        return $this->getLine()->region;
    }

    public function setRegion(SubGeographicalRegion $region): void
    {
        $this->getLine()->region = $region;
    }

    public function removeRegion(): void
    {
        if($this->getLine()->getRegion()->id) {
            $this->region()->dissociate();
        }
        $this->getLine()->region = null;
    }
}
