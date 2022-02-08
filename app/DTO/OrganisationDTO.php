<?php


namespace App\DTO;

use App\Models\Organisation;

use App\DTO\StreetAddressDTO;


/**
 * Class OrganisationDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $electronicAddress
 * @property string $phone1
 * @property string $phone2

 * @property StreetAddressDTO $postalAddress

 *
 */
class OrganisationDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Organisation $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->electronicAddress = $model->electronicAddress;
        $this->phone1 = $model->phone1;
        $this->phone2 = $model->phone2;

        return $this;
    }

    /**
     * @param \App\Models\Organisation $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->postalAddress = (! empty($model->StreetAddress)) ? StreetAddressDTO::instance()->load($model->StreetAddress) : null;

        return $this;
    }
}
