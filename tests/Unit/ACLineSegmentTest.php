<?php

namespace Tests\Unit;

use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Models\Aclinesegment;
use App\Models\Clamp;
use App\Models\Conductance;
use App\Models\Conductor;
use App\Models\Length;
use App\Models\Reactance;
use App\Models\Resistance;
use App\Models\Susceptance;
use App\Models\SwitchObject;
use App\Models\Temperature;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use Tests\Traits\ConductorTrait;

class ACLineSegmentTest extends TestCase
{
    use ConductorTrait;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSaveACLineSegment()
    {
        $aclineSegment= new Aclinesegment();
        $b0ch = factory(Susceptance::class)->create();
        $aclineSegment->setB0ch($b0ch);
        //$bch = factory(Susceptance::class)->create();
        //$aclineSegment->setBch($bch);
        $g0ch = factory(Conductance::class)->create();
        $aclineSegment->setG0ch($g0ch);
        //$gch = factory(Conductance::class)->create();
        //$aclineSegment->setGch($gch);
        //$r = factory(Resistance::class)->create();
        //$aclineSegment->setR($r);
        $r0 = factory(Resistance::class)->create();
        $aclineSegment->setR0($r0);
        //$x = factory(Reactance::class)->create();
        //$aclineSegment->setX($x);
        $x0 = factory(Reactance::class)->create();
        $aclineSegment->setX0($x0);
        $shortCircuitEndTemperature = factory(Temperature::class)->create();
        $aclineSegment->setShortCircuitEndTemperature($shortCircuitEndTemperature);

        $clamp_1 = new Clamp();
        $clamp_1_mrid = $clamp_1->getMRID();
        $clamp_2 = new Clamp();
        $clamp_2_mrid = $clamp_2->getMRID();
        $clamp_3 = new Clamp();
        $clamp_3_mrid = $clamp_3->getMRID();

        $aclineSegment->addClamp($clamp_1);
        $aclineSegment->addClamp($clamp_2);
        $aclineSegment->addClamp($clamp_3);

        $this->assertEquals(3, count($aclineSegment->getClamp()), 'Count of clamps not equal of added');
        $aclineSegment->removeClamp($clamp_2);
        $this->assertEquals(2, count($aclineSegment->getClamp()), 'Count of clamps after deleted must to by less then added ');



        $this->conductor($aclineSegment);
        $aclineSegment = $this->getSaved($aclineSegment->id);
        $this->assertEquals(2, count($aclineSegment->getClamp()), 'Count of clamps not equal of added after save');
        $this->assertEquals($clamp_1_mrid, ($aclineSegment->getClamp()[0]->getMrid()), 'The Mrid of clamp 1 has to be equal added');
        $this->assertEquals($clamp_3_mrid, ($aclineSegment->getClamp()[1]->getMrid()), 'The Mrid of clamp 3 has to be equal added');

        $this->assertInstanceOf(Susceptance::class, $aclineSegment->getB0ch());
        //$this->assertInstanceOf(Susceptance::class, $aclineSegment->getBch());
        $this->assertInstanceOf(Conductance::class, $aclineSegment->getG0ch());
        //$this->assertInstanceOf(Conductance::class, $aclineSegment->getGch());
        //$this->assertInstanceOf(Resistance::class, $aclineSegment->getR());
        $this->assertInstanceOf(Resistance::class, $aclineSegment->getR0());
        //$this->assertInstanceOf(Reactance::class, $aclineSegment->getX());
        $this->assertInstanceOf(Reactance::class, $aclineSegment->getX0());
        $this->assertInstanceOf(Temperature::class, $aclineSegment->getShortCircuitEndTemperature());
    }

    public function getSaved($id): Aclinesegment
    {
        return Aclinesegment::find($id);
    }
}
