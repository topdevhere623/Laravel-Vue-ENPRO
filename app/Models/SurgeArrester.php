<?php

namespace App\Models;

use App\Contracts\CIM\Wires\SurgeArresterInterface;
use App\Traits\AuxiliaryEquipmentTrait;
use App\Traits\BootSaveTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurgeArrester extends Model implements SurgeArresterInterface
{
    use AuxiliaryEquipmentTrait;
    use BootSaveTrait;
    public $auxiliaryEquipment = null;

    protected $bootFields = [
        ['AuxiliaryEquipment','auxiliaryEquipment','belongs','delete']
    ];

    protected $selfIdent = true;

    public function auxiliaryEquipment() : BelongsTo
    {
        return $this->belongsTo(AuxiliaryEquipment::class, 'auxiliary_equipment_id');
    }

    public function getAuxiliaryEquipment() : AuxiliaryEquipment
    {
        if($this->auxiliaryEquipment) return $this->auxiliaryEquipment;
        if($this->auxiliaryEquipment()->get()->get(0)) {
            $this->auxiliaryEquipment = $this->auxiliaryEquipment()->get()->get(0);
        } else $this->auxiliaryEquipment = new AuxiliaryEquipment();
        return $this->auxiliaryEquipment;
    }
}
