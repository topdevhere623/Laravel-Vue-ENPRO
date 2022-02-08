<?php


namespace App\Contracts\CIM\Wires;


use App\Models\Identifiedobject;

interface ACDCTerminalInterface extends IdentifiedObjectInterface
{
    public function getConnected() : bool;
    public function setConnected(bool $connected) : void;

    public function getSequenceNumber(): int;
    public function setSequenceNumber(int $number);

}
