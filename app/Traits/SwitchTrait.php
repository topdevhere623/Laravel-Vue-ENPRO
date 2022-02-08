<?php


namespace App\Traits;


use App\Models\ConductingEquipment;
use App\Models\CurrentFlow;
use App\Models\SwitchPhase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait SwitchTrait
{
    use ConductingEquipmentTrait;

    public function conductingEquipment() : BelongsTo
    {
        return $this->getSwitch()->belongsTo(ConductingEquipment::class, 'conducting_equipment_id');
    }

    public function getConductingEquipment() : ConductingEquipment
    {
        if($this->getSwitch()->ce) return $this->getSwitch()->ce;
        $this->getSwitch()->ce = $this->conductingEquipment()->get()->get(0);
        if(!$this->getSwitch()->ce) $this->getSwitch()->ce = new ConductingEquipment();
        return $this->getSwitch()->ce;

    }
    public function setConductingEquipment(ConductingEquipment $equipment)
    {
        $this->getSwitch()->ce = $equipment;
    }


    public function getNormalOpen(): bool
    {
        return $this->getSwitch()->normal_open;
    }

    public function setNormalOpen(bool $normalOpen): void
    {
        $this->getSwitch()->normal_open = $normalOpen;
    }

    public function ratedCurrent() : BelongsTo
    {
        return $this->getSwitch()->
        belongsTo(CurrentFlow::class, 'current_flows_id');
    }

    public function getRatedCurrent(): ?CurrentFlow
    {
        if($this->getSwitch()->ratedCurrent) return $this->getSwitch()->ratedCurrent;
        $this->getSwitch()->ratedCurrent = $this->ratedCurrent()->get()->get(0);
        return $this->getSwitch()->ratedCurrent;
    }

    public function setRatedCurrent(CurrentFlow $currentFlow): void
    {
        $this->getSwitch()->ratedCurrent = $currentFlow;
    }

    public function getRetained(): bool
    {
        return $this->getSwitch()->retained;
    }

    public function setRetained(bool $retained): void
    {
        $this->getSwitch()->retained = $retained;
    }

    public function getOpen(): bool
    {
        return $this->getSwitch()->open;
    }

    public function setOpen(bool $open): void
    {
        $this->getSwitch()->open = $open;
    }

    public function getLocked(): bool
    {
        return $this->getSwitch()->locked;
    }

    public function setLocked(bool $locked): void
    {
        $this->getSwitch()->locked = $locked;
    }

    public function switchPhase() : HasOne
    {
        return $this->getSwitch()->hasOne(SwitchPhase::class, 'switches_id');
    }

    public function getSwitchPhase(): ? array
    {
        if($this->getSwitch()->switchPhase) return $this->getSwitch()->switchPhase;
        foreach ($this->switchPhase()->get() as $sf) {
            $this->getSwitch()->switchPhase[] = $sf;
        }
        return $this->getSwitch()->switchPhase;

    }

    public function addSwitchPhase(SwitchPhase $switchPhase): void
    {
        if(!in_array($switchPhase, $this->getSwitch()->switchPhase)) {
            $this->getSwitch()->switchPhase[] = $switchPhase;
        }
        // TODO: Implement addSwitchPhase() method.
    }

    public function removeSwitchPhase(SwitchPhase $switchPhase): void
    {
        if(in_array($switchPhase, $this->getSwitch()->switchPhase)) {
            array_splice($this->getSwitch()->switchPhase, array_search($switchPhase, $this->getSwitch()->switchPhase), 1);
        }
    }


}
