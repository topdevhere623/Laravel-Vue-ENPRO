<?php


namespace Tests\Unit;


use App\Contracts\CIM\Wires\ConductingEquipmentInterface;
use App\Contracts\CIM\Wires\EquipmentInterface;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Models\ConductingEquipment;
use App\Models\Equipment;
use App\Models\Identifiedobject;
use App\Models\Name;
use App\Models\PowerSystemResource;
use App\Models\PowerTransformerEnd;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\ConductingEquipmentTrait;
use Tests\Traits\EquipmentTrait;
use Tests\Traits\IdentifiedObjectTrait;

class ConductingEquipmentTest extends TestCase
{

    use ConductingEquipmentTrait;

    public function testCreateConductingEquipment()
    {
        $ce = new ConductingEquipment();
        $this->conductingEquipment($ce);
        $this->assertInstanceOf(ConductingEquipment::class, $ce);


    }

    public function getSaved(int $id) : ConductingEquipmentInterface
    {
        return ConductingEquipment::find($id);
    }

}
