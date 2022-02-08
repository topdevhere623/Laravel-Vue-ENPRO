<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\ConductorInterface;
use App\Contracts\CIM\Wires\SwitchInterface;
use App\Models\Conductor;
use App\Models\CurrentFlow;
use App\Models\Length;
use App\Models\SwitchPhase;
use Illuminate\Database\Eloquent\Model;

trait SwitchTrait
{
    use ConductingEquipmentTrait;

    public function switchcheck(SwitchInterface $switch)
    {
        $faker = \Faker\Factory::create();
        $normalOpen = $faker->boolean();
        /** @var CurrentFlow $ratedCurrent */
        $ratedCurrent = factory(CurrentFlow::class, 1)->make()->get(0);
        $rcValue = $ratedCurrent->getValue();
        $retained = $faker->boolean();
        $open = $faker->boolean();
        $locked = $faker->boolean();

        $switchPhase = new SwitchPhase();
        $switchPhase2 = new SwitchPhase();
        $switchPhase3 = new SwitchPhase();
        $switchPhaseMrid = $switchPhase->getMRID();
        $switchPhaseMrid2 = $switchPhase2->getMRID();
        $switchPhaseMrid3 = $switchPhase3->getMRID();

        $switch->setNormalOpen($normalOpen);
        $switch->setRatedCurrent($ratedCurrent);
        $switch->setRetained($retained);
        $switch->setOpen($open);
        $switch->setLocked($locked);
        $switch->addSwitchPhase($switchPhase);
        $switch->addSwitchPhase($switchPhase2);
        $switch->addSwitchPhase($switchPhase3);

        $this->assertIsBool($switch->getNormalOpen());
        $this->assertEquals($normalOpen, $switch->getNormalOpen());

        $this->assertIsBool($switch->getRetained());
        $this->assertEquals($retained, $switch->getRetained());

        $this->assertIsBool($switch->getOpen());
        $this->assertEquals($open, $switch->getOpen());

        $this->assertIsBool($switch->getLocked());
        $this->assertEquals($locked, $switch->getLocked());

        $this->assertInstanceOf(CurrentFlow::class, $switch->getRatedCurrent());
        $this->assertEquals($ratedCurrent, $switch->getRatedCurrent());

        $this->assertIsArray($switch->getSwitchPhase());
        $this->assertEquals(3, count($switch->getSwitchPhase()));
        $this->assertEquals($switchPhaseMrid, $switch->getSwitchPhase()[0]->getMRID());
        $this->assertEquals($switchPhaseMrid2, $switch->getSwitchPhase()[1]->getMRID());
        $this->assertEquals($switchPhaseMrid3, $switch->getSwitchPhase()[2]->getMRID());



        $this->conductingEquipment($switch);
        $switch = $this->getSaved($switch->id);
        $this->assertIsBool($switch->getNormalOpen());
        $this->assertEquals($normalOpen, $switch->getNormalOpen());

        $this->assertIsBool($switch->getRetained());
        $this->assertEquals($retained, $switch->getRetained());

        $this->assertIsBool($switch->getOpen());
        $this->assertEquals($open, $switch->getOpen());

        $this->assertIsBool($switch->getLocked());
        $this->assertEquals($locked, $switch->getLocked());

        $this->assertInstanceOf(CurrentFlow::class, $switch->getRatedCurrent());
       // $this->assertEquals($ratedCurrent, $switch->getRatedCurrent());

        $this->assertIsArray($switch->getSwitchPhase());
        $this->assertEquals(3, count($switch->getSwitchPhase()));
        $this->assertEquals($switchPhaseMrid, $switch->getSwitchPhase()[0]->getMRID());
        $this->assertEquals($switchPhaseMrid2, $switch->getSwitchPhase()[1]->getMRID());
        $this->assertEquals($switchPhaseMrid3, $switch->getSwitchPhase()[2]->getMRID());
        //$this->assertInstanceOf(Length::class, $conductor->getLength());
    }

}
