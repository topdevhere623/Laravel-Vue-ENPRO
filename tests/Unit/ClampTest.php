<?php

namespace Tests\Unit;

use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Models\Aclinesegment;
use App\Models\Clamp;
use App\Models\Conductor;
use App\Models\Length;
use Tests\TestCase;
use Tests\Traits\ConductingEquipmentTrait;
use Tests\Traits\ConductorTrait;

class ClampTest extends TestCase
{
    use ConductingEquipmentTrait;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSaveClamp()
    {
        $clamp = new Clamp();
        $length = factory(Length::class)->create();
        $clamp->setLength($length);
        $this->assertInstanceOf(Length::class, $clamp->getlengthFromTerminal1(), 'it should to be Length, because we added it before');
        $acLineSegment = new Aclinesegment();
        $acLineSegment->addClamp($clamp);
        $acLineSegmentMrid = $acLineSegment->getMRID();
        $acLineSegment->save();
        $this->assertInstanceOf(Aclinesegment::class, $clamp->getACLineSegment(), 'it should to be ACLineSegment, because we added it before');
        $this->conductingEquipment($clamp);
        $this->assertInstanceOf(Length::class, $clamp->getlengthFromTerminal1(), 'After saving it should to be Length, because we added it before');
        $this->assertInstanceOf(Aclinesegment::class, $clamp->getACLineSegment(), 'After saving it should to be ACLineSegment, because we added it before');
        $this->assertEquals($acLineSegmentMrid, $clamp->getACLineSegment()->getMRID(), 'After saving ACLineSegment  has to have MRID the same added before');
    }

    public function getSaved($id): Clamp
    {
        return Clamp::find($id);
    }
}
