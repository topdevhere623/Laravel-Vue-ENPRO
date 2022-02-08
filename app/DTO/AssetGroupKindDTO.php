<?php


namespace App\DTO;

use App\Models\AssetGroup;


/**
 * Class AssetGroupKindDTO
 * @package App\Models\DTO\PublicDTO
 * @property int $id
 * @property string $description
 *
 */
class AssetGroupKindDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\AssetGroupKind $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->description = $model->description;
        return $this;
    }
}
