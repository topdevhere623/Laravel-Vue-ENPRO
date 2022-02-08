<?php


namespace App\DTO;

use App\Models\AssetGroup;
use App\Models\AssetGroupKind;


/**
 * Class AssetGroupDTO
 * @package App\Models\DTO\PublicDTO
 * @property int $id
 * @property string $name
 * @property AssetGroupKindDTO $kind
 *
 */
class AssetGroupDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\AssetGroup $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->name = $model->Document->IdentifiedObject->name;
        $this->kind = AssetGroupKindDTO::instance()->load($model->AssetGroupKind);
        return $this;
    }
}
