<?php
namespace App\Traits;

use App\Models\IdentifiedObject;
use App\Models\CoordinateSystem;
use App\Models\StreetAddress;
use App\Traits\IdentifiedObjectTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait LocationTrait
 * @package App\Models\Traits
 */
trait LocationTrait
{
    use IdentifiedObjectTrait;


    /**
     * @return string
     */
    public function getDirection() : string
    {
        return $this->getLocation()->direction;
    }

    /**
     * @param string  $direction
     */
    public function setDirection(string $direction) : void
    {
        $this->getLocation()->direction = $direction;
    }

    /**
     * @return IdentifiedObject
     */
    public function getIdentifiedObject() : ? IdentifiedObject
    {
        if($this->getLocation()->parentIdentifiedObject) return $this->getLocation()->parentIdentifiedObject;
        $this->getLocation()->parentIdentifiedObject = $this->IdentifiedObject;
        if(!$this->getLocation()->parentIdentifiedObject) $this->getLocation()->parentIdentifiedObject = new Identifiedobject();
        return $this->getLocation()->parentIdentifiedObject;
    }

    /**
     * @param IdentifiedObject $IdentifiedObject
     */
    public function setIdentifiedObject(IdentifiedObject $IdentifiedObject) : void
    {
        $this->getLocation()->parentIdentifiedObject = $IdentifiedObject;
    }
    /**
     * @return CoordinateSystem|null
     */
    public function getCoordinateSystem() : ?CoordinateSystem
    {
        return $this->CoordinateSystem()->first();
    }

    /**
     * @param CoordinateSystem $CoordinateSystem
     */
    public function setCoordinateSystem(CoordinateSystem $CoordinateSystem) : void
    {
        $this->CoordinateSystem()->associate($CoordinateSystem);
    }
    /**
     * @return StreetAddress|null
     */
    public function getMainAddress() : ?StreetAddress
    {
        return $this->mainAddress()->first();
    }

    /**
     * @param StreetAddress $mainAddress
     */
    public function setMainAddress(StreetAddress $mainAddress) : void
    {
        $this->mainAddress()->associate($mainAddress);
    }


}
