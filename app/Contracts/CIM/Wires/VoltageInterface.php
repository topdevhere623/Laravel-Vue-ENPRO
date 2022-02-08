<?php


namespace App\Contracts\CIM\Wires;


/**
 * Interface Voltage
 * @package App\Contracts\CIM\Wires
 */
interface VoltageInterface extends DataTypeInterface
{
    /**
     * @return int
     */
    public function getValue() : int;

    /**
     * @param int $value
     */
    public function setValue($value) : void;
}
