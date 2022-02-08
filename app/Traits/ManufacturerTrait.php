<?php
namespace App\Traits;

use App\Models\OrganisationRole;
use App\Traits\OrganisationRoleTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait ManufacturerTrait
 * @package App\Models\Traits
 */
trait ManufacturerTrait
{
    use OrganisationRoleTrait;
    


    /**
     * @return OrganisationRole
     */
    public function getOrganisationRole() : ? OrganisationRole
    {
        if($this->getManufacturer()->parentOrganisationRole) return $this->getManufacturer()->parentOrganisationRole;
        $this->getManufacturer()->parentOrganisationRole = $this->OrganisationRole;
        if(!$this->getManufacturer()->parentOrganisationRole) $this->getManufacturer()->parentOrganisationRole = new OrganisationRole();
        return $this->getManufacturer()->parentOrganisationRole;
    }

    /**
     * @param OrganisationRole $OrganisationRole
     */
    public function setOrganisationRole(OrganisationRole $OrganisationRole) : void
    {
        $this->getManufacturer()->parentOrganisationRole = $OrganisationRole;
    }


}
