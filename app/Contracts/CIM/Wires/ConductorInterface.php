<?php


namespace App\Contracts\CIM\Wires;


use App\Models\Length;

interface ConductorInterface extends ConductingEquipmentInterface
{
    public function getLength() :? Length;
    public function setLength(Length $length);



}
