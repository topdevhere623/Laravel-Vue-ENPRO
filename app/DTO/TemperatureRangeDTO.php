<?php


namespace App\DTO;

use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class TemperatureRangeDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property AllDataTypeDTO $minTemperature
 * @property AllDataTypeDTO $maxTemperature
 *
 */
class TemperatureRangeDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\TemperatureRange $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->minTemperature = empty($model->minTemperature) ? null : AllDataTypeDTO::instance()->load($model->minTemperature);
        $this->maxTemperature = empty($model->maxTemperature) ? null : AllDataTypeDTO::instance()->load($model->maxTemperature);


        return $this;
    }


}
