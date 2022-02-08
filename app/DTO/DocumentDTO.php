<?php


namespace App\DTO;

use App\Models\Document;

use App\DTO\IdentifiedObjectDTO;


/**
 * Class DocumentDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property IdentifiedObjectDTO $IdentifiedObject

 *
 */
class DocumentDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Document $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;

        $this->IdentifiedObject = (! empty($model->IdentifiedObject)) ? IdentifiedObjectDTO::instance()->load($model->IdentifiedObject) : null;

        return $this;
    }
}
