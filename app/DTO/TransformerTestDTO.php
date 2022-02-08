<?php


namespace App\DTO;

use App\Models\TransformerTest;

use App\DTO\IdentifiedObjectDTO;


/**
 * Class TransformerTestDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property AllDataTypeDTO $temperature
 * @property IdentifiedObjectDTO $IdentifiedObject

 *
 */
class TransformerTestDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\TransformerTest $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;

        $this->temperature = (! empty($model->temperature)) ? AllDataTypeDTO::instance()->load($model->temperature) : null;
        $this->IdentifiedObject = (! empty($model->IdentifiedObject)) ? IdentifiedObjectDTO::instance()->load($model->IdentifiedObject) : null;

        return $this;
    }
}
