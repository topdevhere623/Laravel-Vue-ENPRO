<?php


namespace App\Contracts\CIM\Wires;


use App\Models\CurrentFlow;
use App\Models\SwitchPhase;

interface SwitchInterface extends ConductingEquipmentInterface
{
    public function getNormalOpen(): bool;
    public function setNormalOpen(bool $normalOpen) : void;

    public function getRatedCurrent() :? CurrentFlow;
    public function setRatedCurrent(CurrentFlow $currentFlow) : void;

    public function getRetained(): bool;
    public function setRetained(bool $retained) : void;
    public function getOpen(): bool;
    public function setOpen(bool $open) : void;
    public function getLocked(): bool;
    public function setLocked(bool $locked) : void;

    public function getSwitchPhase() :? array;
    public function addSwitchPhase(SwitchPhase $switchPhase) : void;
    public function removeSwitchPhase(SwitchPhase $switchPhase): void;


}
