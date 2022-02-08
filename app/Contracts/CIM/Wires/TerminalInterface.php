<?php


namespace App\Contracts\CIM\Wires;


use App\Models\AuxiliaryEquipment;
use App\Models\ConductingEquipment;
use App\Models\ConnectivityNode;
use App\Models\Identifiedobject;
use App\Models\PhaseCode;

interface TerminalInterface extends ACDCTerminalInterface
{

    public function getPhases() :? PhaseCode;
    public function setPhases(PhaseCode $phaseCode) : void;

    public function getConductingEquipment() : ?ConductingEquipment;

    public function getConnectivityNode() :? ConnectivityNode;
    public function setConnectivityNode(ConnectivityNode $connectivityNode) : void;

    public function getAuxiliaryEquipment() :? AuxiliaryEquipment;


}
