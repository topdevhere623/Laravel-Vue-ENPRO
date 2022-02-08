<?php


namespace Tests\Traits;


use App\Contracts\CIM\Asset\AssetInterface;
use App\Contracts\CIM\Wires\EquipmentInterface;
use App\Models\EquipmentContainer;
use App\Models\Location;
use App\Models\PowerSystemResource;

/**
 * @target EquipmentTest
 **/
trait AssetTrait
{
    use IdentifiedObjectTrait;

    public function asset(AssetInterface $asset)
    {
        $this->IdentifiedObject($asset);
        $mrid = $asset->getMRID();
        $asset->setUtcNumber('utcNumber');
        $asset->setSerialNumber('serialNumber');
        $asset->setLotNumber('lotNumber');
        $asset->setPurchasePrice(11.55);
        $asset->setElectronicAddress('cim@toir.ru');
        $asset->setInitialCondition('good');
        $asset->setType('type');
        $location = new Location();
        $asset->setLocation($location);
        $asset->save();
        $id = $asset->id;
        $newAsset = $this->getSaved($id);
        $this->assertEquals('utcNumber', $newAsset->getUtcNumber());
        $this->assertEquals('serialNumber', $newAsset->getSerialNumber());
        $this->assertEquals('lotNumber', $newAsset->getLotNumber());
        $this->assertEquals(11.55, $newAsset->getPurchasePrice());
        $this->assertEquals('cim@toir.ru', $newAsset->getElectronicAddress());
        $this->assertEquals('good', $newAsset->getInitialCondition());
        $this->assertEquals($mrid, $newAsset->getMRID(), 'Mrid of Asset has to be the same');
        $this->assertInstanceOf(Location::class, $newAsset->getLocation(), 'Saved Asset keeps Location');
    }
}
