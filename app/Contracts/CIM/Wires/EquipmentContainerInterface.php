<?php


namespace App\Contracts\CIM\Wires;


interface EquipmentContainerInterface extends ConnectivityNodeContainerInterface
{
    public function getEquipments() : array;

}
