<?php
namespace App\Contracts\CIM\Asset;

use App\Models\IdentifiedObject;
use App\Models\CoordinateSystem;
use App\Models\StreetAddress;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface LocationInterface extends IdentifiedObjectInterface
 * @package App\Contracts\CIM\Work
 */
interface LocationInterface extends IdentifiedObjectInterface
{
    /**
     * @return string
     */
    public function getDirection() : string;

    /**
     * @param string $direction
     */
    public function setDirection(string $direction) : void;

    /**
     * @return IdentifiedObject|null
     */
    public function getIdentifiedObject() : ? IdentifiedObject;

    /**
     * @param IdentifiedObject $IdentifiedObject
     */
    public function setIdentifiedObject(IdentifiedObject $IdentifiedObject) : void;

    /**
     * @return CoordinateSystem|null
     */
    public function getCoordinateSystem() : ? CoordinateSystem;

    /**
     * @param CoordinateSystem $CoordinateSystem
     */
    public function setCoordinateSystem(CoordinateSystem $CoordinateSystem) : void;

    /**
     * @return StreetAddress|null
     */
    public function getMainAddress() : ? StreetAddress;

    /**
     * @param StreetAddress $mainAddress
     */
    public function setMainAddress(StreetAddress $mainAddress) : void;



}
