<?php
namespace App\Contracts\CIM\AssetInfo;

use App\Models\OrganisationRole;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface ManufacturerInterface extends OrganisationRoleInterface
 * @package App\Contracts\CIM\Work
 */
interface ManufacturerInterface extends OrganisationRoleInterface
{

    /**
     * @return OrganisationRole|null
     */
    public function getOrganisationRole() : ? OrganisationRole;

    /**
     * @param OrganisationRole $OrganisationRole
     */
    public function setOrganisationRole(OrganisationRole $OrganisationRole) : void;



}
