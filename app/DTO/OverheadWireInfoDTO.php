<?php


namespace App\DTO;

use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class OverheadWireInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 *
 */
class OverheadWireInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\OverheadWireInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        return $this;
    }
}
