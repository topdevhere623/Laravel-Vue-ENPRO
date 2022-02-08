<?php

namespace App\Models;

use App\Contracts\CIM\Wires\AuxiliaryEquipmentInterface;
use App\Traits\AuxiliaryEquipmentTrait;
use App\Traits\BootSaveTrait;
use Illuminate\Database\Eloquent\Model;

class AuxiliaryEquipment extends Model implements AuxiliaryEquipmentInterface
{
    use AuxiliaryEquipmentTrait;
    use BootSaveTrait;

    public $equipment = null;
    public $terminal = null;

    protected $bootFields = [
        ['Equipment','equipment','belongs','delete'],
        ['Terminal','terminal','belongs','delete']
    ];

    public function getAuxiliaryEquipment() : AuxiliaryEquipment
    {
        return $this;
    }
    //
}
