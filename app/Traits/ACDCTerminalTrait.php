<?php


namespace App\Traits;


use App\Models\Identifiedobject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ACDCTerminalTrait
{
    use IdentifiedObjectTrait;

    public function getConnected(): bool
    {
        return $this->getACDCTerminal()->conneced;
    }

    public function setConnected(bool $connected): void
    {
       $this->getACDCTerminal()->conneced = $connected;
    }

    public function getSequenceNumber(): int
    {
        return $this->getACDCTerminal()->sequence_number;
    }

    public function setSequenceNumber(int $number)
    {
        $this->getACDCTerminal()->sequence_number = $number;
    }

    /**
     * @return Identifiedobject
     */
    public function getIdentifiedObject() : Identifiedobject
    {
        if($this->getACDCTerminal()->io) return $this->getACDCTerminal()->io;
        $this->getACDCTerminal()->io = $this->identifiedobjectBelong()->get()->get(0);
        if(!$this->getACDCTerminal()->io) $this->getACDCTerminal()->io = new Identifiedobject();
        return $this->getACDCTerminal()->io;
    }

    /**
     * @return BelongsTo
     */
    public function identifiedobjectBelong() : BelongsTo
    {
        return $this->getACDCTerminal()->belongsTo(Identifiedobject::class, 'identifiedobject_id');
    }

    /**
     * @param Identifiedobject $identifiedobject
     */
    public function setIdentifiedObject(Identifiedobject $identifiedobject)
    {
        $this->getACDCTerminal()->io = $identifiedobject;
        //$this->identifiedobjectBelong()->associate($identifiedobject);
    }

}
