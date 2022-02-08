<?php


namespace App\DTO;

use App\Models\IdentifiedObject;



/**
 * Class IdentifiedObjectDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $mRID
 * @property string $name
 * @property NameDTO[]|null $names
 * @property string $final_object


 *
 */
class IdentifiedObjectDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\IdentifiedObject $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->mRID = $model->mrid;
        $this->name = $model->name;
        $this->names = empty($model->names()) ? null : $model->names()->get()->map(function($q){return NameDTO::instance()->load($q);});
        $this->final_object = $model->final_object;

        return $this;
    }

    /**
     * @param \App\Models\IdentifiedObject $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);

        return $this;
    }

}
