<?php


namespace App\Traits;
/*
 * @target ConductingEquipment
 */

use App\Models\BaseVoltage;
use App\Models\Equipment;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait ConductingEquipmentTrait
{
    use EquipmentTrait;

    public function equipment() : BelongsTo
    {
        return $this->getConductingEquipment()->belongsTo(Equipment::class);
    }

    public function getEquipment() : Equipment
    {
        if($this->getConductingEquipment()->equipment) return $this->getConductingEquipment()->equipment;
        if($this->equipment()->get()->get(0)) {
            $this->getConductingEquipment()->equipment = $this->equipment()->get()->get(0);
        } else {
            $this->getConductingEquipment()->equipment = new Equipment();
        }
        return $this->getConductingEquipment()->equipment;
    }

    public function baseVoltage() : BelongsTo
    {
        return $this->getConductingEquipment()->belongsTo(BaseVoltage::class, 'basevoltage_id');
    }

    public function getBaseVoltage(): ?BaseVoltage
    {
        if($this->getConductingEquipment()->baseVoltage) return $this->getConductingEquipment()->baseVoltage;
        if($this->baseVoltage()->get()->get(0)) {
            $this->getConductingEquipment()->baseVoltage = $this->baseVoltage()->get()->get(0);
        }
        return $this->baseVoltage()->get()->get(0);
    }

    public function setBaseVoltage(BaseVoltage $voltage)
    {
        $this->getConductingEquipment()->baseVoltage = $voltage;
    }

    public function terminals(): HasOne
    {
        return $this->getConductingEquipment()->hasOne(Terminal::class, 'conductingequipment_id');
    }

    public function getTerminals(): array
    {
        if($this->getConductingEquipment()->terminals) return $this->getConductingEquipment()->terminals;
        if($this->terminals()->get()->count()) {
            foreach ($this->terminals()->get() as $terminal) {
                $this->getConductingEquipment()->terminals[] = $terminal;
            }
        }
        return $this->getConductingEquipment()->terminals;
    }

    public function addTerminal(Terminal $terminal): void
    {
        if(!in_array($terminal, $this->getConductingEquipment()->terminals, true)) {
            $this->getConductingEquipment()->terminals[] = $terminal;
        }

    }

    public function removeTerminal(Terminal $terminal): void
    {
        if(in_array($terminal, $this->getConductingEquipment()->terminals, true)) {
            array_splice($this->getConductingEquipment()->terminals,
                array_search($terminal, $this->getConductingEquipment()->terminals) , 1);
        }
    }


}
