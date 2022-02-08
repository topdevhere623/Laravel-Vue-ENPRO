<?php


namespace App\DTO;

use App\Models\SinglePhaseKind;
use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class WireInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $literal


 *
 */
class SinglePhaseKindDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\SinglePhaseKind $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->literal = $model->literal;
        return $this;
    }

}
