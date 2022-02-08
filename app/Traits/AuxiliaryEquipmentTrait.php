<?php


namespace App\Traits;


use App\Models\Equipment;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AuxiliaryEquipmentTrait
{
    use EquipmentTrait;

    public function equipment() : BelongsTo
    {
        return $this->getAuxiliaryEquipment()->belongsTo(Equipment::class, 'equipment_id');
    }

    public function getEquipment() : Equipment
    {
        if($this->getAuxiliaryEquipment()->equipment) return $this->getAuxiliaryEquipment()->equipment;
        $this->getAuxiliaryEquipment()->equipment = $this->equipment()->get()->get(0);
        if(!$this->getAuxiliaryEquipment()->equipment) $this->getAuxiliaryEquipment()->equipment = new Equipment();
        return $this->getAuxiliaryEquipment()->equipment;

    }
    public function setEquipment(Equipment $equipment)
    {
        $this->getAuxiliaryEquipment()->equipment = $equipment;
    }

    public function terminal() : BelongsTo
    {
        return $this->getAuxiliaryEquipment()->belongsTo(Terminal::class, 'terminal_id');
    }

    public function getTerminal() : Terminal
    {
        if($this->getAuxiliaryEquipment()->terminal) return $this->getAuxiliaryEquipment()->terminal;
        $this->getAuxiliaryEquipment()->terminal = $this->terminal()->get()->get(0);
        if(!$this->getAuxiliaryEquipment()->terminal) {
            $this->getAuxiliaryEquipment()->terminal = new Terminal();
            $this->getAuxiliaryEquipment()->terminal->setSequenceNumber(1);
        }
        return $this->getAuxiliaryEquipment()->terminal;
    }


}
