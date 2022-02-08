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
 * Interface WireInfoInterface extends AssetInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface WireInfoInterface extends AssetInfoInterface
{
    /**
     * @return int
     */
    public function getCoreStrandCount() : int;

    /**
     * @param int $coreStrandCount
     */
    public function setCoreStrandCount(int $coreStrandCount) : void;
    /**
     * @return bool
     */
    public function getInsulated() : bool;

    /**
     * @param bool $insulated
     */
    public function setInsulated(bool $insulated) : void;
    /**
     * @return string
     */
    public function getSizeDescription() : string;

    /**
     * @param string $sizeDescription
     */
    public function setSizeDescription(string $sizeDescription) : void;
    /**
     * @return int
     */
    public function getStrandCount() : int;

    /**
     * @param int $strandCount
     */
    public function setStrandCount(int $strandCount) : void;

    /**
     * @return Length|null
     */
    public function getCoreRadius() : ? Length;

    /**
     * @param Length $coreRadius
     */
    public function setCoreRadius(Length $coreRadius) : void;

    /**
     * @return WireInsulationKind|null
     */
    public function getInsulationMaterial() : ? WireInsulationKind;

    /**
     * @param WireInsulationKind $insulationMaterial
     */
    public function setInsulationMaterial(WireInsulationKind $insulationMaterial) : void;

    /**
     * @return WireMaterialKind|null
     */
    public function getMaterial() : ? WireMaterialKind;

    /**
     * @param WireMaterialKind $material
     */
    public function setMaterial(WireMaterialKind $material) : void;

    /**
     * @return ResistancePerLength|null
     */
    public function getRAC25() : ? ResistancePerLength;

    /**
     * @param ResistancePerLength $rAC25
     */
    public function setRAC25(ResistancePerLength $rAC25) : void;

    /**
     * @return CurrentFlow|null
     */
    public function getRatedCurrent() : ? CurrentFlow;

    /**
     * @param CurrentFlow $ratedCurrent
     */
    public function setRatedCurrent(CurrentFlow $ratedCurrent) : void;

    /**
     * @return AssetInfo|null
     */
    public function getAssetInfo() : ? AssetInfo;

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void;



}
