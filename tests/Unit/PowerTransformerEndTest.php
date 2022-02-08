<?php


namespace Tests\Unit;


use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Models\Identifiedobject;
use App\Models\Name;
use App\Models\PowerSystemResource;
use App\Models\PowerTransformerEnd;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\IdentifiedObjectTrait;

class PowerTransformerEndTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use IdentifiedObjectTrait;

    public function testCreatePowerSystemResource()
    {
        $pte = new PowerTransformerEnd();
        $this->IdentifiedObject($pte);
        $this->assertInstanceOf(PowerTransformerEnd::class, $pte);

    }

    public function getSaved(int $id) : IdentifiedObjectInterface {
        return PowerTransformerEnd::find($id);
    }

}
