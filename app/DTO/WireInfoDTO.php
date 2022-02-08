<?php


namespace App\DTO;

use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class WireInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property integer $coreStrandCount
 * @property boolean $insulated
 * @property string $sizeDescription
 * @property integer $strandCount
 * @property string $name

 * @property AllKindDTO $material
 * @property AllDataTypeDTO $coreRadius
 * @property AllDataTypeDTO $radius
 * @property AllKindDTO $insulationMaterial
 * @property AllKindDTO $insulationThickness
 * @property AllDataTypeDTO $ratedCurrent
 * @property AllDataTypeDTO $rDC20
 * @property AllDataTypeDTO $enproWeightPerLength
 * @property AllDataTypeDTO $enproBreakForce
 * @property GostDTO $enproGost
 * @property CableInfoDTO $CableInfo
 * @property OverheadWireInfoDTO $OverheadWireInfo
 * @property AllDataTypeDTO $nominalVoltage
 * @property array $standardServiceLife
 *
 */
class WireInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\WireInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->coreStrandCount = $model->coreStrandCount;
        $this->insulated = $model->insulated;
        $this->sizeDescription = $model->sizeDescription;
        $this->strandCount = $model->strandCount;

        $this->radius = (! empty($model->radius)) ? AllDataTypeDTO::instance()->load($model->radius) : null;
        $this->coreRadius = (! empty($model->coreRadius)) ? AllDataTypeDTO::instance()->load($model->coreRadius) : null;
        $this->insulationMaterial = (! empty($model->insulationMaterial)) ? AllKindDTO::instance()->load($model->insulationMaterial) : null;
        $this->insulationThickness = (! empty($model->insulationThickness)) ? AllDataTypeDTO::instance()->load($model->insulationThickness) : null;
        $this->material = (! empty($model->material)) ? AllKindDTO::instance()->load($model->material) : null;
        $this->rDC20 = (! empty($model->rDC20)) ? AllDataTypeDTO::instance()->load($model->rDC20) : null;
        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? AllDataTypeDTO::instance()->load($model->ratedCurrent) : null;
        $this->enproWeightPerLength = (! empty($model->enproWeightPerLength)) ? AllDataTypeDTO::instance()->load($model->enproWeightPerLength) : null;
        $this->enproBreakForce = (! empty($model->enproBreakForce)) ? AllDataTypeDTO::instance()->load($model->enproBreakForce) : null;
        $this->enproGost = (! empty($model->enproGost)) ? GostDTO::instance()->load($model->enproGost) : null;
        $this->nominalVoltage = (! empty($model->nominalVoltage)) ? AllDataTypeDTO::instance()->load($model->nominalVoltage) : null;
        $this->standardServiceLife = (! empty($model->standardServiceLife)) ? DurationDTO::instance()->load($model->standardServiceLife) : null;
        $this->CableInfo = (! empty($model->CableInfo)) ? CableInfoDTO::instance()->load($model->CableInfo) : null;
        $this->OverheadWireInfo = (! empty($model->OverheadWireInfo)) ? OverheadWireInfoDTO::instance()->load($model->OverheadWireInfo) : null;


        return $this;
    }

    /**
     * @param \App\Models\WireInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);

        //$this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->load($model->AssetInfo) : null;

        return $this;
    }
}
