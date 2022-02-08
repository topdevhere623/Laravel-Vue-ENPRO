<?php


namespace App\DTO;

use App\Models\Manufacturer;

use App\DTO\OrganisationRoleDTO;


/**
 * Class ManufacturerDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $name

 * @property OrganisationRoleDTO $OrganisationRole

 *
 */
class ManufacturerDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Manufacturer $model
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
     * @param \App\Models\Manufacturer $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->OrganisationRole = (! empty($model->OrganisationRole)) ? OrganisationRoleDTO::instance()->load($model->OrganisationRole) : null;

        return $this;
    }
}
