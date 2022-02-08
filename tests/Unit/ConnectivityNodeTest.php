<?php

namespace Tests\Unit;

use App\Models\ConnectivityNode;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\ConductingEquipmentTrait;
use Tests\Traits\IdentifiedObjectTrait;

class ConnectivityNodeTest extends TestCase
{
    use IdentifiedObjectTrait;

    public function testSaveConnectivityNode()
    {
        $cn  = new ConnectivityNode();
        $this->IdentifiedObject($cn);
    }

    public function getSaved($id = 0) : ConnectivityNode
    {
        return ConnectivityNode::find($id);
    }


}
