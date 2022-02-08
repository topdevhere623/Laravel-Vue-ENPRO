<?php


namespace App\DTO;


/**
 * @property integer id
 * @property AssetInfoDTO|null AssetInfo
 * @property AllDataTypeDTO|null ratedFrequency
 * @property AllKindDTO|null enproClimaticModPlacement
 * @property AllKindDTO|null enproConstructionKind
 * @property AllDataTypeDTO|null enproSecondary1Voltage
 * @property AllDataTypeDTO|null enproSecondary2Voltage
 * @property AllDataTypeDTO|null ratedVoltage
 * @property string accuracyclass
 * @property float massa
 */
class PotentialTransformerInfoDTO extends AbstractPublicDTO
{

    public function load($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo) : null;
        $this->ratedFrequency = (! empty($model->ratedFrequency)) ? AllDataTypeDTO::instance()->load($model->ratedFrequency) : null;
        $this->enproClimaticModPlacement = (! empty($model->enproClimaticModPlacement)) ? AllKindDTO::instance()->load($model->enproClimaticModPlacement) : null;
        $this->enproConstructionKind = (! empty($model->enproConstructionKind)) ? AllKindDTO::instance()->load($model->enproConstructionKind) : null;
        $this->enproSecondary1Voltage = (! empty($model->enproSecondary1Voltage)) ? AllDataTypeDTO::instance()->load($model->enproSecondary1Voltage) : null;
        $this->enproSecondary2Voltage = (! empty($model->enproSecondary2Voltage)) ? AllDataTypeDTO::instance()->load($model->enproSecondary2Voltage) : null;
        $this->ratedVoltage = (! empty($model->ratedVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedVoltage) : null;
        $this->accuracyclass = $model->accuracyclass;
        $this->massa = $model->massa;

        return $this;
    }
}
