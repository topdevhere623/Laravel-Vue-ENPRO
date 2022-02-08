<?php

namespace App\Models;

use App\Contracts\CIM\Wires\SwitchPhaseInterface;
use App\Traits\BootSaveTrait;
use App\Traits\PowerSystemResourceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SwitchPhase extends Model implements SwitchPhaseInterface
{
    use PowerSystemResourceTrait;
    use BootSaveTrait;

    public $phaseSide1;

    public $phaseSide2;

    public $ratedCurrent;

    public $psr;

    public $switchObject;

    protected $bootFields = [
        ['PowerSystemResource','psr','belongs','delete'],
        ['PhaseSide1','phaseSide1','belongs'],
        ['PhaseSide2','phaseSide2','belongs'],
        ['RatedCurrent','ratedCurrent','belongs'],
        //['Switch','switchObject','belongs'],
    ];

    protected $selfIdent = true;

    public function getSwitchPhase() : SwitchPhase
    {
        return $this;
    }

    public function getNormalOpen(): bool
    {
        return $this->getSwitchPhase()->normal_openl;
    }

    public function setNormalOpen(bool $normalOpen): void
    {
        $this->getSwitchPhase()->normal_open = $normalOpen;
    }

    public function phaseSide1():BelongsTo
    {
        return $this->getSwitchPhase()->belongsTo(SinglePhaseKind::class, 'phase_side_1');
    }

    public function phaseSide2():BelongsTo
    {
        return $this->getSwitchPhase()->belongsTo(SinglePhaseKind::class, 'phase_side_2');
    }


    public function getPhaseSide1(): ?SinglePhaseKind
    {
        if($this->getSwitchPhase()->phaseSide1) return $this->getSwitchPhase()->phaseSide1;
        $this->getSwitchPhase()->phaseSide1 = $this->phaseSide1()->get()->get(0);
        return $this->getSwitchPhase()->phaseSide1;

    }

    public function setPhaseSide1(SinglePhaseKind $phaseKind): void
    {
        $this->getSwitchPhase()->phaseSide1 = $phaseKind;
    }

    public function getPhaseSide2(): ?SinglePhaseKind
    {
        if($this->getSwitchPhase()->phaseSide2) return $this->getSwitchPhase()->phaseSide2;
        $this->getSwitchPhase()->phaseSide2 = $this->phaseSide1()->get()->get(0);
        return $this->getSwitchPhase()->phaseSide2;
    }

    public function setPhaseSide2(SinglePhaseKind $phaseKind): void
    {
        $this->getSwitchPhase()->phaseSide2 = $phaseKind;
    }

    public function getClosed(): bool
    {
        return $this->getSwitchPhase()->closed;
    }

    public function setClosed(bool $closed): void
    {
        $this->getSwitchPhase()->closed = $closed;
    }

    public function ratedCurrent() : BelongsTo
    {
        return $this->getSwitchPhase()->
        belongsTo(CurrentFlow::class, 'current_flows_id');
    }

    public function getRatedCurrent(): ?CurrentFlow
    {
        if($this->getSwitchPhase()->ratedCurrent) return $this->getSwitchPhase()->ratedCurrent;
        $this->getSwitchPhase()->ratedCurrent = $this->ratedCurrent()->get()->get(0);
        return $this->getSwitchPhase()->ratedCurrent;
    }

    public function setRatedCurrent(CurrentFlow $currentFlow): void
    {
        $this->getSwitchPhase()->ratedCurrent = $currentFlow;
    }

    public function switchObject() :BelongsTo
    {
        return $this->getSwitchPhase()->belongsTo(SwitchObject::class, 'switches_id');
    }

    public function getSwitch() :? SwitchObject
    {
        if($this->getSwitchPhase()->switchObject) return $this->getSwitchPhase()->switchObject;
        $this->getSwitchPhase()->switchObject = $this->switchObject()->get()->get(0);
        return $this->getSwitchPhase()->switchObject;
        // TODO: Implement getSwitch() method.
    }

    public function setSwitch(SwitchObject $switchObject): void
    {
        $this->getSwitchPhase()->switchObject = $switchObject;
    }

    public function psr() : BelongsTo
    {
        return $this->getSwitchPhase()->belongsTo(PowerSystemResource::class, 'power_system_resources_id');
    }

    public function getPowerSystemResource() : PowerSystemResource
    {
        if($this->getSwitchPhase()->psr) return $this->getSwitchPhase()->psr;
        $this->getSwitchPhase()->psr = $this->psr()->get()->get(0);
        if(!$this->getSwitchPhase()->psr) $this->getSwitchPhase()->psr = new PowerSystemResource();
        return $this->getSwitchPhase()->psr;
    }


    //
}
