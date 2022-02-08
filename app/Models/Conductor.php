<?php

namespace App\Models;

use App\Contracts\CIM\Wires\ConductorInterface;
use App\Traits\BootSaveTrait;
use App\Traits\ConductorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Conductor extends Model implements ConductorInterface
{
    use ConductorTrait;
    use BootSaveTrait;


    protected $bootFields = [
        ['ConductingEquipment','conductingEquipment','belongs','delete'],
        ['Length','length','belongs'],
        ];

    public $length = null;
    /** @var ConductingEquipment */
    public $ce = null;

    public function getConductor() : Conductor
    {
        return $this;
    }

    //
}
