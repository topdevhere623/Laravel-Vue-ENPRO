<?php


namespace App\DTO;

use App\Models\WireInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class CatalogAssetTypeDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property IdentifiedObjectDTO $IdentifiedObject
 *
 */
class CatalogAssetTypeDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\CatalogAssetType $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->IdentifiedObject = IdentifiedObjectDTO::instance()->load($model->IdentifiedObject);
        return $this;
    }
}
