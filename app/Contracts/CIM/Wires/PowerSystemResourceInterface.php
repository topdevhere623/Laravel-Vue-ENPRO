<?php


namespace App\Contracts\CIM\Wires;


use App\Models\Asset;
use App\Models\PSRType;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Interface PowerSystemResourceInterface
 * @package App\Contracts\CIM\Wires
 */
interface PowerSystemResourceInterface extends IdentifiedObjectInterface
{
    /**
     * @return PSRType|null
     */
    public function getPSRType() : ? PSRType;

    /**
     * @param PSRType $type
     */
    public function setPSRType(PSRType $type) : void;

    /**
     * @return HasMany
     */
    public function getAssets() : HasMany;

    /**
     * @param Asset $asset
     */
    public function addAsset(Asset $asset) : void;

    /**
     * @param Asset $asset
     */
    public function removeAsset(Asset $asset) : void;

}
