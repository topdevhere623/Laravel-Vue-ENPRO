<?php
namespace App\Contracts\CIM\AssetInfo;

use App\Models\Length;
use App\Models\WireInsulationKind;
use App\Models\WireMaterialKind;
use App\Models\ResistancePerLength;
use App\Models\CurrentFlow;
use App\Models\AssetInfo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface WireAssemblyInfoInterface extends AssetInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface WireAssemblyInfoInterface extends AssetInfoInterface
{

    /**
     * @return AssetInfo|null
     */
    public function getAssetInfo() : ? AssetInfo;

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void;



}
