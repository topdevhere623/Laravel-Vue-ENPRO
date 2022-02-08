<?php


namespace App\Contracts\CIM\Wires;


use App\Models\BaseVoltage;
use App\Models\Terminal;

interface ConductingEquipmentInterface extends EquipmentInterface
{
    public function getBaseVoltage() :? BaseVoltage;
    public function setBaseVoltage(BaseVoltage  $voltage);

    public function getTerminals() : array;
    public function addTerminal(Terminal $terminal) : void;
    public function removeTerminal(Terminal $terminal) : void;
}
