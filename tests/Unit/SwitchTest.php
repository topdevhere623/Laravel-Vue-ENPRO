<?php

namespace Tests\Unit;

use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Models\Conductor;
use App\Models\SwitchObject;
use Tests\TestCase;
use Tests\Traits\ConductorTrait;
use Tests\Traits\SwitchTrait;

class SwitchTest extends TestCase
{
    use SwitchTrait;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSaveConductor()
    {
        $switchObject = new SwitchObject();
        $this->switchcheck($switchObject);
    }

    public function getSaved($id): SwitchObject
    {
        return SwitchObject::find($id);
    }
}
