<?php


namespace App\Contracts\CIM\Wires;


use App\Models\CurrentFlow;
use App\Models\SinglePhaseKind;
use App\Models\SwitchObject;

interface SwitchPhaseInterface extends PowerSystemResourceInterface
{
    public function getNormalOpen(): bool;
    public function setNormalOpen(bool $normalOpen) : void;

    public function getPhaseSide1() :? SinglePhaseKind;
    public function setPhaseSide1(SinglePhaseKind $phaseKind) : void;

    public function getPhaseSide2() :? SinglePhaseKind;
    public function setPhaseSide2(SinglePhaseKind $phaseKind) : void;

    public function getClosed() : bool;
    public function setClosed(bool $closed) : void;

    public function getRatedCurrent() :? CurrentFlow;
    public function setRatedCurrent(CurrentFlow $currentFlow) : void;

    public function getSwitch() :? SwitchObject;
    public function setSwitch(SwitchObject $switchObject) : void;
}
