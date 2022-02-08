<?php
namespace App\Traits;

use App\Models\Identifiedobject;
use App\Models\Location;
use App\Models\AssetInfo;
use App\Models\Status;
use App\Models\PowerSystemResource;
use App\Traits\IdentifiedObjectTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait AssetTrait
 * @package App\Models\Traits
 */
trait DocumentTrait
{
    use IdentifiedObjectTrait;



    /**
     * @return Identifiedobject
     */
    public function getIdentifiedObject() : ? Identifiedobject
    {
        if($this->getDocument()->parentIdentifiedObject) return $this->getDocument()->parentIdentifiedObject;
        $this->getDocument()->parentIdentifiedObject = $this->IdentifiedObject;
        if(!$this->getDocument()->parentIdentifiedObject) $this->getDocument()->parentIdentifiedObject = new Identifiedobject();
        return $this->getDocument()->parentIdentifiedObject;
    }

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void
    {
        $this->getDocument()->parentIdentifiedObject = $IdentifiedObject;
    }

}
