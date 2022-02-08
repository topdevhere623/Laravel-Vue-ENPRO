<?php
namespace App\Contracts\CIM\AssetInfo;

use App\Models\Organisation;
use App\Models\Identifiedobject;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface OrganisationRoleInterface extends IdentifiedObjectInterface
 * @package App\Contracts\CIM\Work
 */
interface OrganisationRoleInterface extends IdentifiedObjectInterface
{

    /**
     * @return Organisation|null
     */
    public function getOrganisation() : ? Organisation;

    /**
     * @param Organisation $Organisation
     */
    public function setOrganisation(Organisation $Organisation) : void;

    /**
     * @return Identifiedobject|null
     */
    public function getIdentifiedObject() : ? Identifiedobject;

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void;



}
