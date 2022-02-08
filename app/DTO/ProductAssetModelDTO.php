<?php


namespace App\DTO;

use App\Models\ProductAssetModel;

use App\DTO\AssetModelUsageKindDTO;
use App\DTO\ManufacturerDTO;
use App\DTO\AllKindDTO;
use App\DTO\IdentifiedObjectDTO;


/**
 * Class ProductAssetModelDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $catalogueNumber
 * @property string $drawingNumber
 * @property string $instructionManual
 * @property string $modelNumber
 * @property string $modelVersion
 * @property string $styleNumber
 * @property string $name

 * @property AssetModelUsageKindDTO $usageKind
 * @property ManufacturerDTO $Manufacturer
 * @property AllKindDTO $corporateStandardKind
 * @property AllDataTypeDTO $overallLength
 * @property AllDataTypeDTO $weightTotal
 * @property IdentifiedObjectDTO $IdentifiedObject

 *
 */
class ProductAssetModelDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\ProductAssetModel $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->catalogueNumber = $model->catalogueNumber;
        $this->drawingNumber = $model->drawingNumber;
        $this->instructionManual = $model->instructionManual;
        $this->modelNumber = $model->modelNumber;
        $this->modelVersion = $model->modelVersion;
        $this->styleNumber = $model->styleNumber;
        $IdentifiedObject = $model->getIdentifiedObject();
        $this->name = $IdentifiedObject->name;

        return $this;
    }

    /**
     * @param \App\Models\ProductAssetModel $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->usageKind = (! empty($model->AssetModelUsageKind)) ? AssetModelUsageKindDTO::instance()->load($model->AssetModelUsageKind) : null;
        $this->Manufacturer = (! empty($model->Manufacturer)) ? ManufacturerDTO::instance()->load($model->Manufacturer) : null;
        $this->corporateStandardKind = (! empty($model->CorporateStandardKind)) ? AllKindDTO::instance()->load($model->CorporateStandardKind) : null;
        $this->overallLength = (! empty($model->Length)) ? AllDataTypeDTO::instance()->load($model->Length) : null;
        $this->weightTotal = (! empty($model->Mass)) ? AllDataTypeDTO::instance()->load($model->Mass) : null;
        $this->IdentifiedObject = (! empty($model->IdentifiedObject)) ? IdentifiedObjectDTO::instance()->load($model->IdentifiedObject) : null;

        return $this;
    }
}
