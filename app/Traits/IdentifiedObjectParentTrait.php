<?php


namespace App\Traits;


use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Models\ConnectivityNode;
use App\Models\Identifiedobject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait IdentifiedObjectParentTrait
{

    public $io;


    public function identifiedobject() : BelongsTo
    {
        return $this->belongsTo(Identifiedobject::class);
    }

    public function getIdentifiedObject() : Identifiedobject
    {
        if($this->io) return $this->io;
        if(!$this->identifiedObject()->get()->get(0)) {
            $this->io = new Identifiedobject();
        } else {
            $this->io = $this->identifiedObject()->get()->get(0);
        }
        return $this->io;
    }

    public function setIdentifiedObject(Identifiedobject $identifiedobject) : void
    {
        $this->identifiedobject()->associate($identifiedobject);
    }
}
