<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\ConductorInterface;
use App\Models\Conductor;
use App\Models\Length;

trait ConductorTrait
{
    use ConductingEquipmentTrait;

    public function conductor(ConductorInterface $conductor)
    {
        $length = factory(Length::class)->create();
        $conductor->setLength($length);
        $this->assertInstanceOf(Length::class, $conductor->getLength());
        $this->conductingEquipment($conductor);
        $conductor = $this->getSaved($conductor->id);
        $this->assertInstanceOf(Length::class, $conductor->getLength());
    }

}
