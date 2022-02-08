<?php


namespace App\DTO;

use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class GostDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $name
 * @property string $keylink

 *
 */
class GostDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Gost $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->name = $model->name;
        $this->keylink = $model->keylink;

        return $this;
    }


}
