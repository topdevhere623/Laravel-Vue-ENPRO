<?php
namespace App\Traits;

use App\Models\EnproForce;
use App\Models\EnproWeightPerLength;
use App\Models\Length;
use App\Models\WireInsulationKind;
use App\Models\WireMaterialKind;
use App\Models\ResistancePerLength;
use App\Models\CurrentFlow;
use App\Models\AssetInfo;
use App\Traits\AssetInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait WireInfoTrait
 * @package App\Models\Traits
 */
trait WireAssemblyInfoTrait
{
    use AssetInfoTrait;

    /**
     * @return AssetInfo
     */
    public function getAssetInfo() : ? AssetInfo
    {
        if($this->getAssetInfo()->parentAssetInfo) return $this->getAssetInfo()->parentAssetInfo;
        $this->getAssetInfo()->parentAssetInfo = $this->assetInfo;
        if(!$this->getAssetInfo()->parentAssetInfo) $this->getAssetInfo()->parentAssetInfo = new AssetInfo();
        return $this->getAssetInfo()->parentAssetInfo;
    }

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void
    {
        $this->getAssetInfo()->parentAssetInfo = $AssetInfo;
    }


}
