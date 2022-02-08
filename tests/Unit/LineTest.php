<?php


namespace Tests\Unit;


use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Contracts\CIM\Wires\LineInterface;
use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Models\Identifiedobject;
use App\Models\Line;
use App\Models\Name;
use App\Models\PowerSystemResource;
use App\Models\PowerTransformerEnd;
use App\Models\SubGeographicalRegion;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\EquipmentContainerTrait;
use Tests\Traits\IdentifiedObjectTrait;
use Tests\Traits\PowerSystemResourceTrait;

class LineTest extends TestCase
{

    use EquipmentContainerTrait;

    public function testCreateLine()
    {
        $line = new Line();
        $region = new SubGeographicalRegion();
        $region->setDescription('test region description');
        $line->setRegion($region);
        $this->assertEquals($region, $line->getRegion());
        $this->equipmentContainer($line);
        /** @var Line $line */
        $line = Line::find(1);
        $this->assertInstanceOf(Line::class, $line);
        $this->assertInstanceOf(SubGeographicalRegion::class, $line->getRegion());
        $this->assertEquals('test region description', $line->getRegion()->getDescription());
        $line->removeRegion();
        $this->assertEmpty($line->getRegion(), 'Delete region from Line');
        $line->save();
        $id = $line->id;
        $line = Line::find($id);
        $this->assertEmpty($line->getRegion(), 'Delete region from Line should be saved');
        $this->assertInstanceOf(Line::class, $line);
        $line->getName();
    }

    public function getSaved(int $id) : LineInterface {
        return Line::find($id);
    }




}
