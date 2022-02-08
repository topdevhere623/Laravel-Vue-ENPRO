<?php


namespace App\DTO;

use App\Models\CableInfo;

use App\DTO\CableConstructionKindDTO;
use App\DTO\CableOuterJacketKindDTO;
use App\DTO\CableShieldMaterialKindDTO;
use App\DTO\WireInfoDTO;


/**
 * Class CableInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property boolean $isStrandFill
 * @property boolean $sheathAsNeutral
 * @property string $name

 * @property CableConstructionKindDTO $constructionKind
 * @property AllDataTypeDTO $diameterOverCore
 * @property AllDataTypeDTO $diameterOverJacket
 * @property AllDataTypeDTO $nominalTemperature
 * @property AllKindDTO $outerJacketKind
 * @property AllKindDTO $shieldMaterial
 * @property WireInfoDTO $WireInfo
 * @property AllKindDTO $fireSafety

 *
 */
class CableInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\CableInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->constructionKind = empty($model->constructionKind) ? null : AllKindDTO::instance()->load($model->constructionKind);
        $this->fireSafety = empty($model->fireSafety) ? null : AllKindDTO::instance()->load($model->fireSafety);
        $this->shieldMaterial = empty($model->shieldMaterial) ? null : AllKindDTO::instance()->load($model->shieldMaterial);
        $this->outerJacketKind = empty($model->outerJacketKind) ? null : AllKindDTO::instance()->load($model->outerJacketKind);
        $this->diameterOverJacket = (! empty($model->diameterOverJacket)) ? AllDataTypeDTO::instance()->load($model->diameterOverJacket) : null;
        return $this;
    }

    /**
     * @param \App\Models\CableInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->diameterOverCore = (! empty($model->diameterOverCore)) ? AllDataTypeDTO::instance()->load($model->diameterOverCore) : null;
        $this->nominalTemperature = (! empty($model->nominalTemperature)) ? AllDataTypeDTO::instance()->load($model->nominalTemperature) : null;
        $this->WireInfo = WireInfoDTO::instance()->load($model->WireInfo);

        return $this;
    }
}
