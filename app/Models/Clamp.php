<?php

namespace App\Models;

use App\Contracts\CIM\Wires\ClampInterface;
use App\Traits\BootSaveTrait;
use App\Traits\ConductingEquipmentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clamp extends Model implements ClampInterface
{
    use ConductingEquipmentTrait;
    use BootSaveTrait;

    public $conductingEquipment = null;
    public $aclineSegment = null;
    public $length = null;
    protected $selfIdent = true;

    protected $bootFields = [
        ['ConductingEquipment','conductingEquipment','belongs','delete'],
        ['lengthFromTerminal1','length','belongs'],
    ];

    public function conductingEquipment() : BelongsTo
    {
        return $this->belongsTo(ConductingEquipment::class, 'conducting_equipment_id');
    }

    public function getConductingEquipment() : ConductingEquipment
    {
        if($this->conductingEquipment) return $this->conductingEquipment;
        $this->conductingEquipment = $this->conductingEquipment()->get()->get(0);
        if(!$this->conductingEquipment) $this->conductingEquipment = new ConductingEquipment();
        return $this->conductingEquipment;

    }
    public function setConductingEquipment(ConductingEquipment $equipment)
    {
        $this->conductingEquipment = $equipment;
    }

    public function aclineSegment() : BelongsTo
    {
        return $this->belongsTo(Aclinesegment::class, 'aclinesegment_id');
    }

    public function getACLineSegment(): Aclinesegment
    {
        /** @var Aclinesegment $aclineSegment */
        $aclineSegment = $this->aclineSegment()->first();
        return $aclineSegment;
    }

    public function length() : BelongsTo
    {
        return $this->belongsTo(Length::class, 'lengths_id');
    }

    public function getlengthFromTerminal1() :? Length
    {
        if($this->length) return $this->length;
        $this->length = $this->length()->get()->get(0);
        return $this->length;
    }
    public function setLength(Length $length) : void
    {
        $this->length = $length;
    }




    //
}
