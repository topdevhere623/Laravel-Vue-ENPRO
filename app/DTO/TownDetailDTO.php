<?php


namespace App\DTO;

use App\Models\TownDetail;



/**
 * Class TownDetailDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id


 *
 */
class TownDetailDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\TownDetail $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;

        return $this;
    }

    /**
     * @param \App\Models\TownDetail $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);

        return $this;
    }
}
