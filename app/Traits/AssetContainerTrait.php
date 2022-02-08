<?php
namespace App\Traits;

use App\Models\Asset;
use App\Traits\AssetTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait AssetContainerTrait
 * @package App\Models\Traits
 */
trait AssetContainerTrait
{
    use AssetTrait;
public $Assets;



    /**
     * @return Asset
     */
    public function getAsset() : ? Asset
    {
        if($this->getAssetContainer()->parentAsset) return $this->getAssetContainer()->parentAsset;
        $this->getAssetContainer()->parentAsset = $this->Asset;
        if(!$this->getAssetContainer()->parentAsset) $this->getAssetContainer()->parentAsset = new Asset();
        return $this->getAssetContainer()->parentAsset;
    }

    /**
     * @param Asset $Asset
     */
    public function setAsset(Asset $Asset) : void
    {
        $this->getAssetContainer()->parentAsset = $Asset;
    }

    /**
     * @return array
     */
    public function getAssets() : array
    {
        if($this->getAssetContainer()->Assets) return $this->getAssetContainer()->Assets;
        $this->getAssetContainer()->Assets = [];
        foreach($this->getAssetContainer()->Assets()->get() as $relationModel) {
            $this->getAssetContainer()->Assets[] = $relationModel;
        };
        return $this->getAssetContainer()->Assets;
    }

    /**
     * @param Asset $Assets
     */
    public function addAssets(Asset $Assets) : void
    {
        $this->getAssetContainer()->Assets = $this->getAssets();
        if(!in_array($Assets, $this->getAssetContainer()->Assets)) {
            array_push($this->getAssetContainer()->Assets, $Assets);
        }
    }

    /**
     * @param Asset $Assets
     */
    public function removeAssets(Asset $Assets) : void
    {
        array_splice($this->getAssetContainer()->Assets, array_search($Assets, $this->getAssetContainer()->Assets ), 1);
        if($Assets->id) {
            $Assets->delete();
        }
    }


}
