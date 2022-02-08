<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\EquipmentInterface;
use App\Models\EquipmentContainer;
use App\Models\PowerSystemResource;

/**
 * @target EquipmentTest
 **/
trait EquipmentTrait
{
    use PowerSystemResourceTrait;

    public function equipment(EquipmentInterface $equipment)
    {
        $faker = \Faker\Factory::create();
        $this->powerSystemResource($equipment);
        $mrid = $equipment->getMRID();
        $this->assertInstanceOf(PowerSystemResource::class, $equipment->getPowerSystemResource());
        $eq = new EquipmentContainer();
        $eqMrid = $eq->getMRID();
        $equipment->setEquipmentContainer($eq);
        $this->assertInstanceOf(EquipmentContainer::class, $equipment->getEquipmentContainer());
        $randomCount = $faker->numberBetween(1,20);
        $equipmentContainers = factory(EquipmentContainer::class, $randomCount)->make();

        $this->assertIsBool($equipment->getNormallyInService());
        $this->assertIsBool($equipment->getInService());
        $this->assertIsBool($equipment->getAggregate());
        $this->assertIsBool($equipment->getNetworkAnalysisEnabled());

        $nis = $faker->boolean();
        $ins = $faker->boolean();
        $agr = $faker->boolean();
        $nae = $faker->boolean();

        $equipment->setNormallyInService($nis);
        $equipment->setInService($ins);
        $equipment->setAggregate($agr);
        $equipment->setNetworkAnalysisEnabled($nae);

        $this->assertEquals($nis, $equipment->getNormallyInService());
        $this->assertEquals($ins, $equipment->getInService());
        $this->assertEquals($agr, $equipment->getAggregate());
        $this->assertEquals($nae, $equipment->getNetworkAnalysisEnabled());


        foreach ($equipmentContainers as $equipmentContainer) {
            $equipment->addAdditionalEquipmentContainer($equipmentContainer);
        }
        $this->assertEquals($randomCount, count($equipment->getAdditionalEquipmentContainer()), 'Equipment has to get the same quantity of Additional EQ');
        $equipment->save();
        $equipment = $this->getSaved($equipment->id);

        $this->assertEquals($nis, $equipment->getNormallyInService());
        $this->assertEquals($ins, $equipment->getInService());
        $this->assertEquals($agr, $equipment->getAggregate());
        $this->assertEquals($nae, $equipment->getNetworkAnalysisEnabled());

        $this->assertEquals($randomCount, count($equipment->getAdditionalEquipmentContainer()), 'Equipment has to get the same quantity of Additional EQ');
        $this->assertEquals($mrid, $equipment->getMRID(), 'Mrid of Equipment has to be the same');
        $this->assertInstanceOf(EquipmentContainer::class, $equipment->getEquipmentContainer(), 'Saved Equipment keeps EquipmentContainer');
        $this->assertEquals($eqMrid, $equipment->getEquipmentContainer()->getMRID(), 'Saved Equipment->EquipmentContainer the save added');

        $firstAddedContainer = $equipment->getAdditionalEquipmentContainer()[0];
        if($firstAddedContainer) {
            $equipment->removeAdditionalEquipmentContainer($firstAddedContainer);
            $equipment->save();
            $equipment = $this->getSaved($equipment->id);
            $this->assertEquals($randomCount - 1, count($equipment->getAdditionalEquipmentContainer()), 'Remove and save AddContainer');

        }


    }
}
