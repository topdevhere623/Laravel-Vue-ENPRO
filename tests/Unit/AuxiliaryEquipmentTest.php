<?php

namespace Tests\Unit;

use App\Models\AuxiliaryEquipment;
use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Models\Aclinesegment;
use App\Models\Clamp;
use App\Models\Conductor;
use App\Models\Length;
use Tests\TestCase;
use Tests\Traits\AuxiliaryEquipmentTrait;
use Tests\Traits\ConductingEquipmentTrait;
use Tests\Traits\ConductorTrait;

class AuxiliaryEquipmentTest extends TestCase
{
    use AuxiliaryEquipmentTrait;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSaveAuxiliaryEquipment()
    {
        $auxiliaryEquipment = new AuxiliaryEquipment();
        $this->auxiliaryEquipment($auxiliaryEquipment);
    }

    public function getSaved($id): AuxiliaryEquipment
    {
        return AuxiliaryEquipment::find($id);
    }
}
