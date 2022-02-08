<?php
namespace App\Contracts\CIM\Asset;

use App\Models\Identifiedobject;
use App\Models\Location;
use App\Models\AssetInfo;
use App\Models\PowerSystemResource;
use App\Models\Status;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface AssetInterface extends IdentifiedObjectInterface
 * @package App\Contracts\CIM\Work
 */
interface AssetInterface extends IdentifiedObjectInterface
{
    /**
     * @return string
     */
    public function getUtcNumber() : string;

    /**
     * @param string $utcNumber
     */
    public function setUtcNumber(string $utcNumber) : void;
    /**
     * @return string
     */
    public function getSerialNumber() : string;

    /**
     * @param string $serialNumber
     */
    public function setSerialNumber(string $serialNumber) : void;
    /**
     * @return string
     */
    public function getLotNumber() : string;

    /**
     * @param string $lotNumber
     */
    public function setLotNumber(string $lotNumber) : void;
    /**
     * @return float
     */
    public function getPurchasePrice() : float;

    /**
     * @param float $purchasePrice
     */
    public function setPurchasePrice(float $purchasePrice) : void;
    /**
     * @return string
     */
    public function getElectronicAddress() : string;

    /**
     * @param string $electronicAddress
     */
    public function setElectronicAddress(string $electronicAddress) : void;
    /**
     * @return string
     */
    public function getInitialCondition() : string;

    /**
     * @param string $initialCondition
     */
    public function setInitialCondition(string $initialCondition) : void;

    /**
     * @return Identifiedobject|null
     */
    public function getIdentifiedObject() : ? Identifiedobject;

    /**
     * @return string
     */
    public function getType() : string;

    /**
     * @param string $type
     */
    public function setType(string $type) : void;

    /**
     * @return bool
     */
    public function getCritical() : bool;

    /**
     * @param bool $type
     */
    public function setCritical(bool $type) : void;

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void;

    /**
     * @return Location|null
     */
    public function getLocation() : ? Location;

    /**
     * @param Location $Location
     */
    public function setLocation(Location $Location) : void;


    /**
     * @return Status|null
     */
    public function getStatus() : ? Status;

    /**
     * @param Status $status
     */
    public function setStatus(Status $status) : void;


    /**
     * @return array
     */
    public function getPowerSystemResources() : array;

    /**
     * @param PowerSystemResource $PowerSystemResources
     */
    public function addPowerSystemResources(PowerSystemResource $PowerSystemResources) : void;

    /**
     * @param PowerSystemResource $PowerSystemResources
     */
    public function removePowerSystemResources(PowerSystemResource $PowerSystemResources) : void;


    /**
     * @return AssetInfo|null
     */
    public function getAssetInfo() : ? AssetInfo;

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void;
}
