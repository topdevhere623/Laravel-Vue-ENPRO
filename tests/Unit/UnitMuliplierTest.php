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
use App\Models\UnitMultiplier;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\EquipmentContainerTrait;
use Tests\Traits\IdentifiedObjectTrait;
use Tests\Traits\PowerSystemResourceTrait;

class UnitMuliplierTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use EquipmentContainerTrait;

    public function testUnitMultiplier()
    {
        $units = factory(UnitMultiplier::class, 10)->make();
        /** @var UnitMultiplier $unit */
        foreach ($units as $unit) {
            $this->assertIsNumeric($unit->getValue());
            $this->assertIsString($unit->getLiteral());
            $this->assertIsString($unit->getDescription());
        }
        $faker = \Faker\Factory::create();
        $value = $faker->numberBetween(-10000, 10000);
        $literal = $faker->text(5);
        $description = $faker->text(255);

        $unit = new UnitMultiplier();
        $unit->setValue($value);
        $unit->setDescription($description);
        $unit->setLiteral($literal);
        $unit->save();
        $unit = UnitMultiplier::find($unit->id);
        $this->assertEquals($value, $unit->getValue());
        $this->assertEquals($literal, $unit->getLiteral());
        $this->assertEquals($description, $unit->getDescription());



    }




}
