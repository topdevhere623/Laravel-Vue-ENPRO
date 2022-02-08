<?php


namespace App\Contracts\CIM\Wires;


use App\Models\Clamp;
use App\Models\Conductance;
use App\Models\Conductor;
use App\Models\Reactance;
use App\Models\Resistance;
use App\Models\Susceptance;
use App\Models\Temperature;

interface ACLineSegmentInterface extends ConductorInterface
{
    /**
     * @return Susceptance
     */
    public function getB0ch(): ?Susceptance;


    /**
     * @param Susceptance $b0ch
     */
    public function setB0ch(?Susceptance $b0ch): void;


    /**
     * @return Susceptance
     */
    public function getBch(): ?Susceptance;


    /**
     * @param Susceptance $bch
     */
    public function setBch(?Susceptance $bch): void;


    /**
     * @return Conductance
     */
    public function getG0ch(): ?Conductance;


    /**
     * @param Conductance $g0ch
     */
    public function setG0ch(?Conductance $g0ch): void;


    /**
     * @return Conductance
     */
    public function getGch(): ?Conductance;


    /**
     * @param Conductance $gch
     */
    public function setGch(?Conductance $gch): void;


    /**
     * @return Resistance
     */
    public function getR(): ?Resistance;


    /**
     * @param Resistance $r
     */
    public function setR(?Resistance $r): void;


    /**
     * @return Resistance
     */
    public function getR0(): ?Resistance;


    /**
     * @param Resistance $r0
     */
    public function setR0(?Resistance $r0): void;


    /**
     * @return Reactance
     */
    public function getX(): ?Reactance;


    /**
     * @param Reactance $x
     */
    public function setX(?Reactance $x): void;


    /**
     * @return Reactance
     */
    public function getX0(): ?Reactance;


    /**
     * @param Reactance $x0
     */
    public function setX0(?Reactance $x0): void;


    /**
     * @return Temperature
     */
    public function getShortCircuitEndTemperature(): ?Temperature;


    /**
     * @param Temperature $shortCircuitEndTemperature
     */
    public function setShortCircuitEndTemperature(?Temperature $shortCircuitEndTemperature): void;

    public function addClamp(Clamp $clamp) : void;

    public function removeClamp(Clamp $clamp) : void;

    public function getClamp() : array;



}
