<?php


namespace App\Contracts\CIM\Wires;


use App\Models\SubGeographicalRegion;

interface LineInterface extends EquipmentContainerInterface
{
    public function getRegion() :? SubGeographicalRegion;
    public function setRegion(SubGeographicalRegion $region) : void;
    public function removeRegion(): void;
}
