<?php
namespace App\Traits;

use App\Models\AssetModelUsageKind;
use App\Models\Manufacturer;
use App\Models\CorporateStandardKind;
use App\Models\Length;
use App\Models\Mass;
use App\Models\Identifiedobject;
use App\Traits\IdentifiedObjectTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait ProductAssetModelTrait
 * @package App\Models\Traits
 */
trait ProductAssetModelTrait
{
    use IdentifiedObjectTrait;


    /**
     * @return string
     */
    public function getCatalogueNumber() : string
    {
        return $this->getProductAssetModel()->catalogueNumber;
    }

    /**
     * @param string  $catalogueNumber
     */
    public function setCatalogueNumber(string $catalogueNumber) : void
    {
        $this->getProductAssetModel()->catalogueNumber = $catalogueNumber;
    }
    /**
     * @return string
     */
    public function getDrawingNumber() : string
    {
        return $this->getProductAssetModel()->drawingNumber;
    }

    /**
     * @param string  $drawingNumber
     */
    public function setDrawingNumber(string $drawingNumber) : void
    {
        $this->getProductAssetModel()->drawingNumber = $drawingNumber;
    }
    /**
     * @return string
     */
    public function getInstructionManual() : string
    {
        return $this->getProductAssetModel()->instructionManual;
    }

    /**
     * @param string  $instructionManual
     */
    public function setInstructionManual(string $instructionManual) : void
    {
        $this->getProductAssetModel()->instructionManual = $instructionManual;
    }
    /**
     * @return string
     */
    public function getModelNumber() : string
    {
        return $this->getProductAssetModel()->modelNumber;
    }

    /**
     * @param string  $modelNumber
     */
    public function setModelNumber(string $modelNumber) : void
    {
        $this->getProductAssetModel()->modelNumber = $modelNumber;
    }
    /**
     * @return string
     */
    public function getModelVersion() : string
    {
        return $this->getProductAssetModel()->modelVersion;
    }

    /**
     * @param string  $modelVersion
     */
    public function setModelVersion(string $modelVersion) : void
    {
        $this->getProductAssetModel()->modelVersion = $modelVersion;
    }
    /**
     * @return string
     */
    public function getStyleNumber() : string
    {
        return $this->getProductAssetModel()->styleNumber;
    }

    /**
     * @param string  $styleNumber
     */
    public function setStyleNumber(string $styleNumber) : void
    {
        $this->getProductAssetModel()->styleNumber = $styleNumber;
    }

    /**
     * @return AssetModelUsageKind|null
     */
    public function getUsageKind() : ?AssetModelUsageKind
    {
        return $this->usageKind()->first();
    }

    /**
     * @param AssetModelUsageKind $usageKind
     */
    public function setUsageKind(AssetModelUsageKind $usageKind) : void
    {
        $this->usageKind()->associate($usageKind);
    }
    /**
     * @return Manufacturer|null
     */
    public function getManufacturer() : ?Manufacturer
    {
        return $this->Manufacturer()->first();
    }

    /**
     * @param Manufacturer $Manufacturer
     */
    public function setManufacturer(Manufacturer $Manufacturer) : void
    {
        $this->Manufacturer()->associate($Manufacturer);
    }
    /**
     * @return CorporateStandardKind|null
     */
    public function getCorporateStandardKind() : ?CorporateStandardKind
    {
        return $this->corporateStandardKind()->first();
    }

    /**
     * @param CorporateStandardKind $corporateStandardKind
     */
    public function setCorporateStandardKind(CorporateStandardKind $corporateStandardKind) : void
    {
        $this->corporateStandardKind()->associate($corporateStandardKind);
    }
    /**
     * @return Length|null
     */
    public function getOverallLength() : ?Length
    {
        return $this->overallLength()->first();
    }

    /**
     * @param Length $overallLength
     */
    public function setOverallLength(Length $overallLength) : void
    {
        $this->overallLength()->associate($overallLength);
    }
    /**
     * @return Mass|null
     */
    public function getWeightTotal() : ?Mass
    {
        return $this->weightTotal()->first();
    }

    /**
     * @param Mass $weightTotal
     */
    public function setWeightTotal(Mass $weightTotal) : void
    {
        $this->weightTotal()->associate($weightTotal);
    }
    /**
     * @return Identifiedobject
     */
    public function getIdentifiedObject() : ? Identifiedobject
    {
        if($this->getProductAssetModel()->parentIdentifiedObject) return $this->getProductAssetModel()->parentIdentifiedObject;
        $this->getProductAssetModel()->parentIdentifiedObject = $this->IdentifiedObject;
        if(!$this->getProductAssetModel()->parentIdentifiedObject) $this->getProductAssetModel()->parentIdentifiedObject = new Identifiedobject();
        return $this->getProductAssetModel()->parentIdentifiedObject;
    }

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void
    {
        $this->getProductAssetModel()->parentIdentifiedObject = $IdentifiedObject;
    }


}
