<?php


namespace App\DTO;

use App\Models\AssetInfo;

use App\DTO\ProductAssetModelDTO;
use App\DTO\IdentifiedObjectDTO;


/**
 * Class AssetInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $name

 * @property ProductAssetModelDTO $ProductAssetModel
 * @property IdentifiedObjectDTO $IdentifiedObject
 * @property CatalogAssetTypeDTO $CatalogAssetType

 *
 */
class AssetInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\AssetInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $IdentifiedObject = $model->getIdentifiedObject();
        $this->name = $IdentifiedObject->name;

        return $this;
    }

    /**
     * @param \App\Models\AssetInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->ProductAssetModel = (! empty($model->ProductAssetModel)) ? ProductAssetModelDTO::instance()->load($model->ProductAssetModel) : null;
        $this->IdentifiedObject = (! empty($model->IdentifiedObject)) ? IdentifiedObjectDTO::instance()->load($model->IdentifiedObject) : null;

        return $this;
    }

    /**
     * @param \App\Models\AssetInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadCatalogAssetType($model)
    {
        $this->id = $model->id;
        $this->CatalogAssetType = (! empty($model->CatalogAssetType)) ? CatalogAssetTypeDTO::instance()->load($model->CatalogAssetType) : null;

        return $this;
    }

    /**
     * @param \App\Models\AssetInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadIdentifiedObject($model)
    {
        $this->id = $model->id;
        $this->IdentifiedObject = (! empty($model->IdentifiedObject)) ? IdentifiedObjectDTO::instance()->load($model->IdentifiedObject) : null;
        return $this;
    }
}
