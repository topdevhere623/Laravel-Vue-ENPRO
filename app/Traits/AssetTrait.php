<?php
namespace App\Traits;

use App\Models\Identifiedobject;
use App\Models\Location;
use App\Models\AssetInfo;
use App\Models\Status;
use App\Models\PowerSystemResource;
use App\Traits\IdentifiedObjectTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait AssetTrait
 * @package App\Models\Traits
 */
trait AssetTrait
{
    use IdentifiedObjectTrait;
    public $PowerSystemResources;


    /**
     * @return string
     */
    public function getUtcNumber() : string
    {
        return $this->getAsset()->utc_number;
    }

    /**
     * @param string  $utcNumber
     */
    public function setUtcNumber(string $utcNumber) : void
    {
        $this->getAsset()->utc_number = $utcNumber;
    }
    /**
     * @return string
     */
    public function getSerialNumber() : string
    {
        return $this->getAsset()->serialnumber;
    }

    /**
     * @param string  $serialNumber
     */
    public function setSerialNumber(string $serialNumber) : void
    {
        $this->getAsset()->serialnumber = $serialNumber;
    }
    /**
     * @return string
     */
    public function getLotNumber() : string
    {
        return $this->getAsset()->lot_number;
    }

    /**
     * @param string  $lotNumber
     */
    public function setLotNumber(string $lotNumber) : void
    {
        $this->getAsset()->lot_number = $lotNumber;
    }
    /**
     * @return float
     */
    public function getPurchasePrice() : float
    {
        return $this->getAsset()->purchaseprice;
    }

    /**
     * @param float  $purchasePrice
     */
    public function setPurchasePrice(float $purchasePrice) : void
    {
        $this->getAsset()->purchaseprice = $purchasePrice;
    }
    /**
     * @return string
     */
    public function getElectronicAddress() : string
    {
        return $this->getAsset()->electronic_address;
    }

    /**
     * @param string  $electronicAddress
     */
    public function setElectronicAddress(string $electronicAddress) : void
    {
        $this->getAsset()->electronic_address = $electronicAddress;
    }
    /**
     * @return string
     */
    public function getInitialCondition() : string
    {
        return $this->getAsset()->initialcondition;
    }

    /**
     * @param string  $initialCondition
     */
    public function setInitialCondition(string $initialCondition) : void
    {
        $this->getAsset()->initialcondition = $initialCondition;
    }

    /**
     * @return Identifiedobject
     */
    public function getIdentifiedObject() : ? Identifiedobject
    {
        if($this->getAsset()->parentIdentifiedObject) return $this->getAsset()->parentIdentifiedObject;
        $this->getAsset()->parentIdentifiedObject = $this->IdentifiedObject;
        if(!$this->getAsset()->parentIdentifiedObject) $this->getAsset()->parentIdentifiedObject = new Identifiedobject();
        return $this->getAsset()->parentIdentifiedObject;
    }

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void
    {
        $this->getAsset()->parentIdentifiedObject = $IdentifiedObject;
    }
    /**
     * @return Location|null
     */
    public function getLocation() : ?Location
    {
        if($this->getAsset()->location) return $this->getAsset()->location;
        if($this->location()->first()) {
            $this->getAsset()->location = $this->location()->first();
        } else {
            $this->getAsset()->location = new Location();
        }
        return $this->getAsset()->location;
    }

    /**
     * @param Location $Location
     */
    public function setLocation(Location $Location) : void
    {
        $this->getAsset()->location = $Location;
    }

    /**
     * @return Status|null
     */
    public function getStatus() : ?Status
    {
        return $this->status()->first();
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status) : void
    {
        $this->status()->associate($status);
    }

    /**
     * @return array
     */
    public function getPowerSystemResources() : array
    {
        if($this->getAsset()->PowerSystemResources) return $this->getAsset()->PowerSystemResources;
        $this->getAsset()->PowerSystemResources = [];
        foreach($this->getAsset()->PowerSystemResources()->get() as $relationModel) {
            $this->getAsset()->PowerSystemResources[] = $relationModel;
        };
        return $this->getAsset()->PowerSystemResources;
    }

    /**
     * @param PowerSystemResource $PowerSystemResources
     */
    public function addPowerSystemResources(PowerSystemResource $PowerSystemResources) : void
    {
        $this->getAsset()->PowerSystemResources = $this->getPowerSystemResources();
        if(!in_array($PowerSystemResources, $this->getAsset()->PowerSystemResources)) {
            array_push($this->getAsset()->PowerSystemResources, $PowerSystemResources);
        }
    }

    /**
     * @param PowerSystemResource $PowerSystemResources
     */
    public function removePowerSystemResources(PowerSystemResource $PowerSystemResources) : void
    {
        array_splice($this->getAsset()->PowerSystemResources, array_search($PowerSystemResources, $this->getAsset()->PowerSystemResources ), 1);
        if($PowerSystemResources->id) {
            $PowerSystemResources->delete();
        }
    }

    /**
     * @param string  $type
     */
    public function setType(string $type) : void
    {
        $this->getAsset()->type = $type;
    }
    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->getAsset()->type;
    }

    /**
     * @param boolean  $critical
     */
    public function setCritical(bool $critical) : void
    {
        $this->getAsset()->critical = $critical;
    }

    /**
     * @return bool
     */
    public function getCritical() : bool
    {
        return $this->getAsset()->critical;
    }

    /**
     * @return AssetInfo|null
     */
    public function getAssetInfo() : ?AssetInfo
    {
        if($this->getAsset()->AssetInfo) return $this->getAsset()->AssetInfo;
        if($this->location()->first()) {
            $this->getAsset()->AssetInfo = $this->location()->first();
        } else {
            $this->getAsset()->AssetInfo = new Location();
        }
        return $this->getAsset()->AssetInfo;
    }

    /**
     * @param AssetInfo $Location
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void
    {
        $this->getAsset()->AssetInfo = $AssetInfo;
    }
}
