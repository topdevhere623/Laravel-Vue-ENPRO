<?php


namespace App\DTO;

use App\Models\SwitchInfo;
use App\DTO\CurrentFlowDTO;
use App\DTO\TemperatureRangeDTO;
use App\DTO\GostDTO;
use App\DTO\AssetInfoDTO;

/**
 * Class CurrenttransformerinfoDTO
 * @package App\DTO
 * @property CurrentFlowDTO $ratedCurrent
 * @property \App\DTO\AssetInfoDTO|null AssetInfo
 * @property AllDataTypeDTO|null enproMaxVoltage
 * @property AllDataTypeDTO|null ratedFrequency
 * @property AllKindDTO|null enproClimaticModPlacement
 * @property RatioDTO|null nominalRatio
 * @property AllDataTypeDTO|null ratedVoltage
 * @property string accuracyclass
 * @property int corecount
 */

class CurrenttransformerinfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Currenttransformerinfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo) : null;

        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? CurrentFlowDTO::instance()->load($model->ratedCurrent) : null;
        $this->ratedFrequency = (! empty($model->ratedFrequency)) ? AllDataTypeDTO::instance()->load($model->ratedFrequency) : null;
        $this->enproMaxVoltage = (! empty($model->enproMaxVoltage)) ? AllDataTypeDTO::instance()->load($model->enproMaxVoltage) : null;
        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? CurrentFlowDTO::instance()->load($model->ratedCurrent) : null;
        $this->enproClimaticModPlacement = (! empty($model->enproClimaticModPlacement)) ? AllKindDTO::instance()->load($model->enproClimaticModPlacement) : null;
        $this->nominalRatio = (! empty($model->nominalRatio)) ? RatioDTO::instance()->load($model->nominalRatio) : null;
        $this->ratedVoltage = (! empty($model->ratedVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedVoltage) : null;
        $this->corecount = $model->corecount;
        $this->accuracyclass = $model->accuracyclass;
        return $this;
    }
}
