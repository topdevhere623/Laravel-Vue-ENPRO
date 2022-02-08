<?php
namespace App\Contracts\CIM\Asset;

use App\Models\Identifiedobject;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface DocumentInterface extends IdentifiedObjectInterface
 * @package App\Contracts\CIM\Work
 */
interface DocumentInterface extends IdentifiedObjectInterface
{

    /**
     * @return IdentifiedObject|null
     */
    public function getIdentifiedObject() : ? IdentifiedObject;

    /**
     * @param IdentifiedObject $IdentifiedObject
     */
    public function setIdentifiedObject(IdentifiedObject $IdentifiedObject) : void;



}
