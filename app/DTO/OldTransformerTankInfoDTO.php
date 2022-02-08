<?php


namespace App\DTO;

use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class OldTransformerTankInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property TransformerTankInfoDTO $TransformerTankInfo
 * @property AllKindDTO $constructionKind
 * @property AllDataTypeDTO $coreCoilsWeight
 * @property AllKindDTO $coreKind
 * @property AllKindDTO $function
 * @property AllKindDTO $coolingKind
 * @property AllDataTypeDTO $enproFullWeight
 * @property AllDataTypeDTO $enproOilWeight
 * @property TemperatureRangeDTO $enproTemperatureRange
 * @property GostDTO $enproGost
 */
class OldTransformerTankInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\OldTransformerTankInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->TransformerTankInfo = TransformerTankInfoDTO::instance()->load($model->TransformerTankInfo);
        $this->constructionKind = empty($model->constructionKind) ? null : AllKindDTO::instance()->load($model->constructionKind);
        $this->coreCoilsWeight = empty($model->coreCoilsWeight) ? null : AllDataTypeDTO::instance()->load($model->coreCoilsWeight);
        $this->coreKind = empty($model->coreKind) ? null : AllKindDTO::instance()->load($model->coreKind);
        $this->function = empty($model->function) ? null : AllKindDTO::instance()->load($model->function);
        $this->coolingKind = empty($model->coolingKind) ? null : AllKindDTO::instance()->load($model->coolingKind);
        $this->enproFullWeight = empty($model->enproFullWeight) ? null : AllDataTypeDTO::instance()->load($model->enproFullWeight);
        $this->enproOilWeight = empty($model->enproOilWeight) ? null : AllDataTypeDTO::instance()->load($model->enproOilWeight);
        $this->enproTemperatureRange = empty($model->enproTemperatureRange) ? null : TemperatureRangeDTO::instance()->load($model->enproTemperatureRange);
        $this->enproGost = empty($model->enproGost) ? null : GostDTO::instance()->load($model->enproGost);
        return $this;
    }


}
