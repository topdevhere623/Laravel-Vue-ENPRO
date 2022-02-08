<?php


namespace App\Traits;


use App\Models\ConductingEquipment;
use App\Models\Length;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ConductorTrait
{
    use ConductingEquipmentTrait;

    public function conductingEquipment() : BelongsTo
    {
        return $this->getConductor()->belongsTo(ConductingEquipment::class);
    }

    public function getConductingEquipment() : ConductingEquipment
    {
        if($this->getConductor()->ce) return $this->getConductor()->ce;
        $this->getConductor()->ce = $this->conductingEquipment()->get()->get(0);
        if(!$this->getConductor()->ce) $this->getConductor()->ce = new ConductingEquipment();
        return $this->getConductor()->ce;
    }

    public function length() : BelongsTo
    {
        return $this->getConductor()->belongsTo(Length::class);
    }

    public function getLength() :? Length
    {
        if($this->getConductor()->length) return $this->getConductor()->length;
        $this->getConductor()->length = $this->length()->get()->get(0);
        return $this->getConductor()->length;
    }
    public function setLength(Length $length)
    {
        $this->getConductor()->length = $length;
    }

}
