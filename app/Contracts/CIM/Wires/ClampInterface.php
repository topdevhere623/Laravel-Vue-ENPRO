<?php


namespace App\Contracts\CIM\Wires;

use App\Models\Aclinesegment;
use App\Models\Length;

interface ClampInterface extends ConductingEquipmentInterface
{
    public function getlengthFromTerminal1() : ?Length;
    public function setLength(Length $length): void;

    public function getACLineSegment() : Aclinesegment;
}
