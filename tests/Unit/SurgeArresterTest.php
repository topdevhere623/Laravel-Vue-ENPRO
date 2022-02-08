<?php

namespace Tests\Unit;

use App\Models\AuxiliaryEquipment;
use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Models\Aclinesegment;
use App\Models\Clamp;
use App\Models\Conductor;
use App\Models\Length;
use App\Models\SurgeArrester;
use Tests\TestCase;
use Tests\Traits\AuxiliaryEquipmentTrait;
use Tests\Traits\ConductingEquipmentTrait;
use Tests\Traits\ConductorTrait;

class SurgeArresterTest extends TestCase
{
    use AuxiliaryEquipmentTrait;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSurgeArrester()
    {
        $auxiliaryEquipment = new SurgeArrester();
        $this->auxiliaryEquipment($auxiliaryEquipment);
    }

    public function getSaved($id): SurgeArrester
    {
        return SurgeArrester::find($id);
    }
}
