<?php
namespace App\Traits;

use App\Models\CableConstructionKind;
use App\Models\Length;
use App\Models\Temperature;
use App\Models\CableOuterJacketKind;
use App\Models\CableShieldMaterialKind;
use App\Models\WireInfo;
use App\Traits\WireInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait CableInfoTrait
 * @package App\Models\Traits
 */
trait CableInfoTrait
{
    use WireInfoTrait;


    /**
     * @return bool
     */
    public function getIsStrandFill() : bool
    {
        return $this->getCableInfo()->isStrandFill;
    }

    /**
     * @param bool  $isStrandFill
     */
    public function setIsStrandFill(bool $isStrandFill) : void
    {
        $this->getCableInfo()->isStrandFill = $isStrandFill;
    }
    /**
     * @return bool
     */
    public function getSheathAsNeutral() : bool
    {
        return $this->getCableInfo()->sheathAsNeutral;
    }

    /**
     * @param bool  $sheathAsNeutral
     */
    public function setSheathAsNeutral(bool $sheathAsNeutral) : void
    {
        $this->getCableInfo()->sheathAsNeutral = $sheathAsNeutral;
    }

    /**
     * @return CableConstructionKind|null
     */
    public function getConstructionKind() : ?CableConstructionKind
    {
        return $this->constructionKind()->first();
    }

    /**
     * @param CableConstructionKind $constructionKind
     */
    public function setConstructionKind(CableConstructionKind $constructionKind) : void
    {
        $this->constructionKind()->associate($constructionKind);
    }
    /**
     * @return Length|null
     */
    public function getDiameterOverCore() : ?Length
    {
        return $this->diameterOverCore()->first();
    }

    /**
     * @param Length $diameterOverCore
     */
    public function setDiameterOverCore(Length $diameterOverCore) : void
    {
        $this->diameterOverCore()->associate($diameterOverCore);
    }
    /**
     * @return Temperature|null
     */
    public function getNominalTemperature() : ?Temperature
    {
        return $this->nominalTemperature()->first();
    }

    /**
     * @param Temperature $nominalTemperature
     */
    public function setNominalTemperature(Temperature $nominalTemperature) : void
    {
        $this->nominalTemperature()->associate($nominalTemperature);
    }
    /**
     * @return CableOuterJacketKind|null
     */
    public function getOuterJacketKind() : ?CableOuterJacketKind
    {
        return $this->outerJacketKind()->first();
    }

    /**
     * @param CableOuterJacketKind $outerJacketKind
     */
    public function setOuterJacketKind(CableOuterJacketKind $outerJacketKind) : void
    {
        $this->outerJacketKind()->associate($outerJacketKind);
    }
    /**
     * @return CableShieldMaterialKind|null
     */
    public function getShieldMaterial() : ?CableShieldMaterialKind
    {
        return $this->shieldMaterial()->first();
    }

    /**
     * @param CableShieldMaterialKind $shieldMaterial
     */
    public function setShieldMaterial(CableShieldMaterialKind $shieldMaterial) : void
    {
        $this->shieldMaterial()->associate($shieldMaterial);
    }
    /**
     * @return WireInfo
     */
    public function getWireInfo() : ? WireInfo
    {
        if($this->getCableInfo()->parentWireInfo) return $this->getCableInfo()->parentWireInfo;
        $this->getCableInfo()->parentWireInfo = $this->WireInfo;
        if(!$this->getCableInfo()->parentWireInfo) $this->getCableInfo()->parentWireInfo = new WireInfo();
        return $this->getCableInfo()->parentWireInfo;
    }

    /**
     * @param WireInfo $WireInfo
     */
    public function setWireInfo(WireInfo $WireInfo) : void
    {
        $this->getCableInfo()->parentWireInfo = $WireInfo;
    }


}
