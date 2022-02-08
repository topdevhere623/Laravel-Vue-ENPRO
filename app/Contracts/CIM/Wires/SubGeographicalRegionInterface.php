<?php


namespace App\Contracts\CIM\Wires;


use App\Models\GeographicalRegion;

interface SubGeographicalRegionInterface extends IdentifiedObjectInterface
{
    public function getRegion() :? GeographicalRegion;
    public function setRegion(GeographicalRegion $region) : void;
    public function removeRegion() : void;
}
