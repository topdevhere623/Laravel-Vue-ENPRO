<?php


namespace Tests\Unit;


use App\Contracts\CIM\Wires\EquipmentInterface;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Models\Equipment;
use App\Models\Identifiedobject;
use App\Models\Name;
use App\Models\PowerSystemResource;
use App\Models\PowerTransformerEnd;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\EquipmentTrait;
use Tests\Traits\IdentifiedObjectTrait;

class EquipmentTest extends TestCase
{

    use EquipmentTrait;

    public function testCreateEquipment()
    {
        $eq = new Equipment();
        $this->equipment($eq);
        $fakerEquipment = factory(Equipment::class, 1)->make()->get(0);
        $this->equipment($fakerEquipment);
        $this->assertInstanceOf(Equipment::class, $eq);


    }

    public function getSaved(int $id) : EquipmentInterface {
        return Equipment::find($id);
    }

}
