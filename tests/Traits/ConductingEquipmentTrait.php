<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\ConductingEquipmentInterface;
use App\Models\BaseVoltage;
use App\Models\ConductingEquipment;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Model;

/**
 * @target  ConductingEquipmentTest
 */
trait ConductingEquipmentTrait
{
    use EquipmentTrait;

    public function conductingEquipment(ConductingEquipmentInterface $equipment)
    {
        $this->equipment($equipment);
        $baseVoltage = new BaseVoltage();
        $equipment->setBaseVoltage($baseVoltage);
        $this->assertEquals($baseVoltage, $equipment->getBaseVoltage());
        $terminal = new Terminal();
        $equipment->addTerminal($terminal);
        $this->assertEquals(1, count($equipment->getTerminals()));

        $terminal2 = new Terminal();
        $equipment->addTerminal($terminal2);

        $terminal3 = new Terminal();
        $equipment->addTerminal($terminal3);

        $this->assertEquals(3, count($equipment->getTerminals()));

        $equipment->removeTerminal($terminal3);

        $this->assertEquals(2, count($equipment->getTerminals()));

        $equipment->save();

        $equipment = $this->getSaved($equipment->id);

        $this->assertEquals(2, count($equipment->getTerminals()), 'saved terminals from ConductingEquipment');

        $this->assertInstanceOf(BaseVoltage::class, $equipment->getBaseVoltage(), 'saved BaseVoltage from ConductingEquipment');



    }
}
