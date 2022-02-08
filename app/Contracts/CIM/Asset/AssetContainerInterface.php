<?php
namespace App\Contracts\CIM\Asset;

use App\Models\Asset;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface AssetContainerInterface extends AssetInterface
 * @package App\Contracts\CIM\Work
 */
interface AssetContainerInterface extends AssetInterface
{

    /**
     * @return Asset|null
     */
    public function getAsset() : ? Asset;

    /**
     * @param Asset $Asset
     */
    public function setAsset(Asset $Asset) : void;


    /**
     * @return array
     */
    public function getAssets() : array;

    /**
     * @param Asset $Assets
     */
    public function addAssets(Asset $Assets) : void;

    /**
     * @param Asset $Assets
     */
    public function removeAssets(Asset $Assets) : void;


}
