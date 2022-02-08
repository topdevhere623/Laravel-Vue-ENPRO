<?php


namespace App\DTO;

use App\Models\WireMaterialKind;



/**
 * Class WireMaterialKindDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $value
 * @property string $description


 *
 */
class WireMaterialKindDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\WireMaterialKind $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->value = $model->value;
        $this->description = $model->description;

        return $this;
    }

    /**
     * @param \App\Models\WireMaterialKind $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);

        return $this;
    }
}
