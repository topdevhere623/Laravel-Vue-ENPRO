<?php


namespace App\DTO;

use App\Models\OrganisationRole;

use App\DTO\OrganisationDTO;
use App\DTO\IdentifiedObjectDTO;


/**
 * Class OrganisationRoleDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $name

 * @property OrganisationDTO $Organisation
 * @property IdentifiedObjectDTO $IdentifiedObject

 *
 */
class OrganisationRoleDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\OrganisationRole $model
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
     * @param \App\Models\OrganisationRole $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->Organisation = (! empty($model->Organisation)) ? OrganisationDTO::instance()->load($model->Organisation) : null;
        $this->IdentifiedObject = (! empty($model->IdentifiedObject)) ? IdentifiedObjectDTO::instance()->load($model->IdentifiedObject) : null;

        return $this;
    }
}
