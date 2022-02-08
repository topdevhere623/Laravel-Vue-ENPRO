<?php


namespace App\DTO;

use App\Models\StreetAddress;

use App\DTO\StreetDetailDTO;
use App\DTO\TownDetailDTO;


/**
 * Class StreetAddressDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property StreetDetailDTO $streetDetail
 * @property TownDetailDTO $townDetail

 *
 */
class StreetAddressDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\StreetAddress $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;

        return $this;
    }

    /**
     * @param \App\Models\StreetAddress $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->streetDetail = (! empty($model->StreetDetail)) ? StreetDetailDTO::instance()->load($model->StreetDetail) : null;
        $this->townDetail = (! empty($model->TownDetail)) ? TownDetailDTO::instance()->load($model->TownDetail) : null;

        return $this;
    }
}
