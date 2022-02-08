<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\AuxiliaryEquipmentInterface;
use App\Contracts\CIM\Wires\ConductingEquipmentInterface;
use App\Models\BaseVoltage;
use App\Models\ConductingEquipment;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Model;

/**
 * @target  ConductingEquipmentTest
 */
trait AuxiliaryEquipmentTrait
{
    use EquipmentTrait;

    public function auxiliaryEquipment(AuxiliaryEquipmentInterface $equipment)
    {
        $this->assertInstanceOf(Terminal::class, $equipment->getTerminal(), 'A terminal in AuxiliaryEquipment should be present!');
        $terminalMrid = $equipment->getTerminal()->getMRID();
        $this->equipment($equipment);
        $equipment = $this->getSaved($equipment->id);
        $this->assertInstanceOf(Terminal::class, $equipment->getTerminal(), 'A terminal in AuxiliaryEquipment should be present after save!');
        $this->assertEquals($terminalMrid, $equipment->getTerminal()->getMRID(), 'The saved terminal in AuxiliaryEquipment should to be the same added');

    }
}
