<?php


namespace App\Contracts\CIM\Wires;


use App\Models\Terminal;

interface AuxiliaryEquipmentInterface extends EquipmentInterface
{
    public function getTerminal() : Terminal;
}
