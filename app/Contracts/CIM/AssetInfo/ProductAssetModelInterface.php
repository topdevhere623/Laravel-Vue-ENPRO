<?php
namespace App\Contracts\CIM\AssetInfo;

use App\Models\AssetModelUsageKind;
use App\Models\Manufacturer;
use App\Models\CorporateStandardKind;
use App\Models\Length;
use App\Models\Mass;
use App\Models\Identifiedobject;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface ProductAssetModelInterface extends IdentifiedObjectInterface
 * @package App\Contracts\CIM\Work
 */
interface ProductAssetModelInterface extends IdentifiedObjectInterface
{
    /**
     * @return string
     */
    public function getCatalogueNumber() : string;

    /**
     * @param string $catalogueNumber
     */
    public function setCatalogueNumber(string $catalogueNumber) : void;
    /**
     * @return string
     */
    public function getDrawingNumber() : string;

    /**
     * @param string $drawingNumber
     */
    public function setDrawingNumber(string $drawingNumber) : void;
    /**
     * @return string
     */
    public function getInstructionManual() : string;

    /**
     * @param string $instructionManual
     */
    public function setInstructionManual(string $instructionManual) : void;
    /**
     * @return string
     */
    public function getModelNumber() : string;

    /**
     * @param string $modelNumber
     */
    public function setModelNumber(string $modelNumber) : void;
    /**
     * @return string
     */
    public function getModelVersion() : string;

    /**
     * @param string $modelVersion
     */
    public function setModelVersion(string $modelVersion) : void;
    /**
     * @return string
     */
    public function getStyleNumber() : string;

    /**
     * @param string $styleNumber
     */
    public function setStyleNumber(string $styleNumber) : void;

    /**
     * @return AssetModelUsageKind|null
     */
    public function getUsageKind() : ? AssetModelUsageKind;

    /**
     * @param AssetModelUsageKind $usageKind
     */
    public function setUsageKind(AssetModelUsageKind $usageKind) : void;

    /**
     * @return Manufacturer|null
     */
    public function getManufacturer() : ? Manufacturer;

    /**
     * @param Manufacturer $Manufacturer
     */
    public function setManufacturer(Manufacturer $Manufacturer) : void;

    /**
     * @return CorporateStandardKind|null
     */
    public function getCorporateStandardKind() : ? CorporateStandardKind;

    /**
     * @param CorporateStandardKind $corporateStandardKind
     */
    public function setCorporateStandardKind(CorporateStandardKind $corporateStandardKind) : void;

    /**
     * @return Length|null
     */
    public function getOverallLength() : ? Length;

    /**
     * @param Length $overallLength
     */
    public function setOverallLength(Length $overallLength) : void;

    /**
     * @return Mass|null
     */
    public function getWeightTotal() : ? Mass;

    /**
     * @param Mass $weightTotal
     */
    public function setWeightTotal(Mass $weightTotal) : void;

    /**
     * @return Identifiedobject|null
     */
    public function getIdentifiedObject() : ? Identifiedobject;

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void;



}
