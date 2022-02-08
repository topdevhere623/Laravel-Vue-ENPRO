<?php
namespace App\Traits;

use App\Models\Temperature;
use App\Models\IdentifiedObject;
use App\Traits\IdentifiedObjectTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait TransformerTestTrait
 * @package App\Models\Traits
 */
trait TransformerTestTrait
{
    use IdentifiedObjectTrait;
    


    /**
     * @return Temperature|null
     */
    public function getTemperature() : ?Temperature
    {
        return $this->temperature()->first();
    }

    /**
     * @param Temperature $temperature
     */
    public function setTemperature(Temperature $temperature) : void
    {
        $this->temperature()->associate($temperature);
    }
    /**
     * @return IdentifiedObject
     */
    public function getIdentifiedObject() : ? IdentifiedObject
    {
        if($this->getTransformerTest()->parentIdentifiedObject) return $this->getTransformerTest()->parentIdentifiedObject;
        $this->getTransformerTest()->parentIdentifiedObject = $this->IdentifiedObject;
        if(!$this->getTransformerTest()->parentIdentifiedObject) $this->getTransformerTest()->parentIdentifiedObject = new IdentifiedObject();
        return $this->getTransformerTest()->parentIdentifiedObject;
    }

    /**
     * @param IdentifiedObject $IdentifiedObject
     */
    public function setIdentifiedObject(IdentifiedObject $IdentifiedObject) : void
    {
        $this->getTransformerTest()->parentIdentifiedObject = $IdentifiedObject;
    }


}
