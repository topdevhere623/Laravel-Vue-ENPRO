<?php


namespace App\DTO;

use App\Models\SinglePhaseKind;
use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;
use App\DTO\WireInfoDTO;


/**
 * Class WireInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property SinglePhaseKindDTO $phaseInfo
 * @property WireInfoDTO $WireInfo

 *
 */
class WirePhaseInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\WirePhaseInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->phaseInfo = empty($model->phaseInfo) ? null : EnumKindDTO::instance()->load($model->phaseInfo);
        $this->WireInfo = WireInfoDTO::instance()->load($model->WireInfo);
        return $this;
    }

}
