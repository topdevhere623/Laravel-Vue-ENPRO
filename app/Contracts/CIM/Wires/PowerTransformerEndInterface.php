<?php


namespace App\Contracts\CIM\Wires;


use App\Models\TransformerEnd;
use App\Models\WindingConnection;

interface PowerTransformerEndInterface extends TransformerEndInterface
{
    public function getB(): float;
    public function setB(float $b) : void;

    public function getB0(): float;
    public function setB0(float $b0) : void;

    public function setConnectionKind( WindingConnection $windingConnection) : void;
    public function getConnectionKind() : WindingConnection;

    public function getG(): float;
    public function setG(float $g) : void;

    public function getG0(): float;
    public function setG0(float $g0) : void;

    public function setPhaseAngleClock(int $phaseAngleClock) : void;
    public function getPhaseAngleClock() : int;

    public function getR(): float;
    public function setR(float $r) : void;

    public function getR0(): float;
    public function setR0(float $r0) : void;

    public function getRatedS(): float;
    public function setRatedS(float $ratedS) : void;

    public function getRatedU(): float;
    public function setRatedU(float $ratedU) : void;

    public function getX(): float;
    public function setX(float $x) : void;

    public function getX0(): float;
    public function setX0(float $x0) : void;

}
