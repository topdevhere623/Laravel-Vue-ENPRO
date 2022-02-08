<?php


namespace App\Contracts\CIM\Wires;


use App\Models\PowerTransformerEnd;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface PowerTransformerInterface
{
    public function getBeforeShCircuitHighestOperatingCurrent() : float;
    public function setBeforeShCircuitHighestOperatingCurrent(float $beforeShCircuitHighestOperatingCurrent) : void;

    public function getBeforeShCircuitHighestOperatingVoltage() : float;
    public function setBeforeShCircuitHighestOperatingVoltage(float $beforeShCircuitHighestOperatingVoltage) : void;

    public function getBeforeShortCircuitAnglePf() : float;
    public function setBeforeShortCircuitAnglePf(float $beforeShortCircuitAnglePf) : void;

    public function getHighSideMinOperatingU() : float;
    public function setHighSideMinOperatingU(float $highSideMinOperatingU) : void;

    public function getIsPartOfGeneratorUnit() : bool;
    public function setIsPartOfGeneratorUnit(bool $partOfGeneratorUnit) : void;

    public function getOperationalValuesConsidered() : bool;
    public function setOperationalValuesConsidered(bool $operationalValuesConsidered) : void;

    public function getVectorGroup() : string;
    public function setVectorGroup(string $vectorGroup) : void;

    public function getPowerTransformerEnds() : HasMany;
    public function setPowerTransformerEnds(HasMany $powerTransformerEnds) : void;
    public function addPowerTransformerEnd(PowerTransformerEnd $powerTransformerEnd) : bool;
    public function removePowerTransformerEnd(PowerTransformerEnd $powerTransformerEnd) : bool;

    public function getTransformerTanks() : HasMany;
    public function setTransformerTanks(HasMany $powerTransformerTanks): void;
    public function addTransformerTank();
    public function removeTransformerTank();


}
