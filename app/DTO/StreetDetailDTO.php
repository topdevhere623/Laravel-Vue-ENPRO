<?php


namespace App\DTO;

use App\Models\StreetDetail;



/**
 * Class StreetDetailDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id


 *
 */
class StreetDetailDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\StreetDetail $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;

        return $this;
    }

    /**
     * @param \App\Models\StreetDetail $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);

        return $this;
    }
}
