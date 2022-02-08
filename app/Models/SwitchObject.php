<?php

namespace App\Models;

use App\Contracts\CIM\Wires\SwitchInterface;
use App\Traits\BootSaveTrait;
use App\Traits\SwitchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SwitchObject extends Model implements SwitchInterface
{
    use SwitchTrait;
    use BootSaveTrait;
    protected $table = 'switches';

    public $ce;
    public $ratedCurrent;
    public $switchPhase = [];

    protected $bootFields = [
        ['ConductingEquipment','conductingEquipment','belongs','delete'],
        ['RatedCurrent','ratedCurrent','belongs'],
        ['SwitchPhase','switchPhase','hasone', null, 'switchObject']
    ];


    public function getSwitch() : SwitchObject
    {
        return $this;
    }

}
