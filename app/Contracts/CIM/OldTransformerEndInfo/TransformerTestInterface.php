<?php
namespace App\Contracts\CIM\OldTransformerEndInfo;

use App\Models\Temperature;
use App\Models\IdentifiedObject;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface TransformerTestInterface extends IdentifiedObjectInterface
 * @package App\Contracts\CIM\Work
 */
interface TransformerTestInterface extends IdentifiedObjectInterface
{

    /**
     * @return Temperature|null
     */
    public function getTemperature() : ? Temperature;

    /**
     * @param Temperature $temperature
     */
    public function setTemperature(Temperature $temperature) : void;

    /**
     * @return IdentifiedObject|null
     */
    public function getIdentifiedObject() : ? IdentifiedObject;

    /**
     * @param IdentifiedObject $IdentifiedObject
     */
    public function setIdentifiedObject(IdentifiedObject $IdentifiedObject) : void;



}
