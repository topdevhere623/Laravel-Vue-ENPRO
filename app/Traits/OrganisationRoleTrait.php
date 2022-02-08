<?php
namespace App\Traits;

use App\Models\Organisation;
use App\Models\Identifiedobject;
use App\Traits\IdentifiedObjectTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait OrganisationRoleTrait
 * @package App\Models\Traits
 */
trait OrganisationRoleTrait
{
    use IdentifiedObjectTrait;



    /**
     * @return Organisation|null
     */
    public function getOrganisation() : ?Organisation
    {
        return $this->Organisation()->first();
    }

    /**
     * @param Organisation $Organisation
     */
    public function setOrganisation(Organisation $Organisation) : void
    {
        $this->Organisation()->associate($Organisation);
    }
    /**
     * @return Identifiedobject
     */
    public function getIdentifiedObject() : ? Identifiedobject
    {
        if($this->getOrganisationRole()->parentIdentifiedObject) return $this->getOrganisationRole()->parentIdentifiedObject;
        $this->getOrganisationRole()->parentIdentifiedObject = $this->IdentifiedObject;
        if(!$this->getOrganisationRole()->parentIdentifiedObject) $this->getOrganisationRole()->parentIdentifiedObject = new Identifiedobject();
        return $this->getOrganisationRole()->parentIdentifiedObject;
    }

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void
    {
        $this->getOrganisationRole()->parentIdentifiedObject = $IdentifiedObject;
    }


}
