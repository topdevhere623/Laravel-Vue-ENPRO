<?php


namespace App\Contracts\CIM\Wires;


use App\Models\UnitMultiplier;
use App\Models\UnitSymbol;

/**
 * Interface DataTypeInterface
 * @package App\Contracts\CIM\Wires
 */
interface DataTypeInterface
{
    /**
     * @return UnitSymbol
     */
    public function getUnit(): UnitSymbol;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return UnitMultiplier
     */
    public function getMultiplier(): UnitMultiplier;

    /**
     * @param UnitSymbol $literal
     */
    public function setUnit(UnitSymbol $literal): void;

    /**
     * @param $value
     */
    public function setValue($value): void;

    /**
     * @param UnitMultiplier $multiplier
     */
    public function setMultiplier(UnitMultiplier $multiplier): void;

}
