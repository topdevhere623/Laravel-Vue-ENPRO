<?php


namespace App\DTO;

use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class TransformerTankInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property AssetInfoDTO $AssetInfo
 * @property TransformerEndInfoDTO[] $TransformerEndInfo
 */
class TransformerTankInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\TransformerTankInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo);
        $this->TransformerEndInfo = empty($model->TransformerEndInfo) ? null : $model->TransformerEndInfo->map(function($q){
            return TransformerEndInfoDTO::instance()->load($q);
        });
        return $this;
    }


}
