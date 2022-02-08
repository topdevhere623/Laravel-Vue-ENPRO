<?php

namespace Tests\Unit;

use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Models\Conductor;
use Tests\TestCase;
use Tests\Traits\ConductorTrait;

class ConductorTest extends TestCase
{
    use ConductorTrait;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSaveConductor()
    {
        $conductor = new Conductor();
        $this->conductor($conductor);
    }

    public function getSaved($id): Conductor
    {
        return Conductor::find($id);
    }
}
