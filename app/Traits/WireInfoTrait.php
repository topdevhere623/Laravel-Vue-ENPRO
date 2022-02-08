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
trait WireInfoTrait
{
    use AssetInfoTrait;


    /**
     * @return int
     */
    public function getCoreStrandCount() : int
    {
        return $this->getWireInfo()->coreStrandCount;
    }

    /**
     * @param int  $coreStrandCount
     */
    public function setCoreStrandCount(int $coreStrandCount) : void
    {
        $this->getWireInfo()->coreStrandCount = $coreStrandCount;
    }
    /**
     * @return bool
     */
    public function getInsulated() : bool
    {
        return $this->getWireInfo()->insulated;
    }

    /**
     * @param bool  $insulated
     */
    public function setInsulated(bool $insulated) : void
    {
        $this->getWireInfo()->insulated = $insulated;
    }
    /**
     * @return string
     */
    public function getSizeDescription() : string
    {
        return $this->getWireInfo()->sizeDescription;
    }

    /**
     * @param string  $sizeDescription
     */
    public function setSizeDescription(string $sizeDescription) : void
    {
        $this->getWireInfo()->sizeDescription = $sizeDescription;
    }
    /**
     * @return int
     */
    public function getStrandCount() : int
    {
        return $this->getWireInfo()->strandCount;
    }

    /**
     * @param int  $strandCount
     */
    public function setStrandCount(int $strandCount) : void
    {
        $this->getWireInfo()->strandCount = $strandCount;
    }

    /**
     * @return Length|null
     */
    public function getCoreRadius() : ?Length
    {
        return $this->coreRadius()->first();
    }

    /**
     * @param Length $coreRadius
     */
    public function setCoreRadius(Length $coreRadius) : void
    {
        $this->coreRadius()->associate($coreRadius);
    }
    /**
     * @return WireInsulationKind|null
     */
    public function getInsulationMaterial() : ?WireInsulationKind
    {
        return $this->insulationMaterial()->first();
    }

    /**
     * @param WireInsulationKind $insulationMaterial
     */
    public function setInsulationMaterial(WireInsulationKind $insulationMaterial) : void
    {
        $this->insulationMaterial()->associate($insulationMaterial);
    }
    /**
     * @return WireMaterialKind|null
     */
    public function getMaterial() : ?WireMaterialKind
    {
        return $this->material()->first();
    }

    /**
     * @param WireMaterialKind $material
     */
    public function setMaterial(WireMaterialKind $material) : void
    {
        $this->material()->associate($material);
    }
    /**
     * @return ResistancePerLength|null
     */
    public function getRAC25() : ?ResistancePerLength
    {
        return $this->rAC25()->first();
    }

    /**
     * @param ResistancePerLength $rAC25
     */
    public function setRAC25(ResistancePerLength $rAC25) : void
    {
        $this->rAC25()->associate($rAC25);
    }


    /**
     * @param EnproForce $enproFirce
     */
    public function setEnpoForce(EnproForce $enproForce) : void
    {
        $this->enproForce()->associate($enproForce);
    }

    /**
     * @return EnproForce|null
     */
    public function getEnpoForce() : ?EnproForce
    {
        return $this->enproForce()->first();
    }

    /**
     * @param EnproWeightPerLength $enproWeightPerLength
     */
    public function setEnproWeightPerLength(EnproWeightPerLength $enproWeightPerLength) : void
    {
        $this->enproWeightPerLength()->associate($enproWeightPerLength);
    }

    /**
     * @return EnproWeightPerLength|null
     */
    public function getEnproWeightPerLength() : ?EnproWeightPerLength
    {
        return $this->enproWeightPerLength()->first();
    }

    /**
     * @return CurrentFlow|null
     */
    public function getRatedCurrent() : ?CurrentFlow
    {
        return $this->ratedCurrent()->first();
    }

    /**
     * @param CurrentFlow $ratedCurrent
     */
    public function setRatedCurrent(CurrentFlow $ratedCurrent) : void
    {
        $this->ratedCurrent()->associate($ratedCurrent);
    }


    public function setEnproGost(string $enproGost) : void
    {
        $this->enpro_gost = $enproGost;
    }

    public function getEnproGost() :? string
    {
        return $this->enpro_gost;
    }

    /**
     * @return AssetInfo
     */
    public function getAssetInfo() : ? AssetInfo
    {
        if($this->getWireInfo()->parentAssetInfo) return $this->getWireInfo()->parentAssetInfo;
        $this->getWireInfo()->parentAssetInfo = $this->AssetInfo;
        if(!$this->getWireInfo()->parentAssetInfo) $this->getWireInfo()->parentAssetInfo = new AssetInfo();
        return $this->getWireInfo()->parentAssetInfo;
    }

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void
    {
        $this->getWireInfo()->parentAssetInfo = $AssetInfo;
    }


}
