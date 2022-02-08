<?php


namespace App\Contracts\CIM\Wires;


interface BaseVoltageInterface extends IdentifiedObjectInterface
{
    public function getNominalVoltage() :? VoltageInterface;

    public function setNominalVoltage(VoltageInterface $voltage);

}
