<?php


namespace App\DTO;

use App\Models\Location;

use App\DTO\IdentifiedObjectDTO;


/**
 * Class LocationDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $name

 * @property IdentifiedObjectDTO $IdentifiedObject

 *
 */
class LocationDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Location $model
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
     * @param \App\Models\Location $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->IdentifiedObject = (! empty($model->IdentifiedObject)) ? IdentifiedObjectDTO::instance()->load($model->IdentifiedObject) : null;

        return $this;
    }
}
