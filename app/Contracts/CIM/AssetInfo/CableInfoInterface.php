<?php
namespace App\Contracts\CIM\AssetInfo;

use App\Models\CableConstructionKind;
use App\Models\Length;
use App\Models\Temperature;
use App\Models\CableOuterJacketKind;
use App\Models\CableShieldMaterialKind;
use App\Models\WireInfo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface CableInfoInterface extends WireInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface CableInfoInterface extends WireInfoInterface
{
    /**
     * @return bool
     */
    public function getIsStrandFill() : bool;

    /**
     * @param bool $isStrandFill
     */
    public function setIsStrandFill(bool $isStrandFill) : void;
    /**
     * @return bool
     */
    public function getSheathAsNeutral() : bool;

    /**
     * @param bool $sheathAsNeutral
     */
    public function setSheathAsNeutral(bool $sheathAsNeutral) : void;

    /**
     * @return CableConstructionKind|null
     */
    public function getConstructionKind() : ? CableConstructionKind;

    /**
     * @param CableConstructionKind $constructionKind
     */
    public function setConstructionKind(CableConstructionKind $constructionKind) : void;

    /**
     * @return Length|null
     */
    public function getDiameterOverCore() : ? Length;

    /**
     * @param Length $diameterOverCore
     */
    public function setDiameterOverCore(Length $diameterOverCore) : void;

    /**
     * @return Temperature|null
     */
    public function getNominalTemperature() : ? Temperature;

    /**
     * @param Temperature $nominalTemperature
     */
    public function setNominalTemperature(Temperature $nominalTemperature) : void;

    /**
     * @return CableOuterJacketKind|null
     */
    public function getOuterJacketKind() : ? CableOuterJacketKind;

    /**
     * @param CableOuterJacketKind $outerJacketKind
     */
    public function setOuterJacketKind(CableOuterJacketKind $outerJacketKind) : void;

    /**
     * @return CableShieldMaterialKind|null
     */
    public function getShieldMaterial() : ? CableShieldMaterialKind;

    /**
     * @param CableShieldMaterialKind $shieldMaterial
     */
    public function setShieldMaterial(CableShieldMaterialKind $shieldMaterial) : void;

    /**
     * @return WireInfo|null
     */
    public function getWireInfo() : ? WireInfo;

    /**
     * @param WireInfo $WireInfo
     */
    public function setWireInfo(WireInfo $WireInfo) : void;



}
