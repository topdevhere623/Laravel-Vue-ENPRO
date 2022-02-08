<?php

namespace App\Models;

use App\Contracts\CIM\Wires\ConnectivityNodeInterface;
use App\Traits\BootSaveTrait;
use App\Traits\IdentifiedObjectParentTrait;
use App\Traits\IdentifiedObjectTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConnectivityNode extends Model implements ConnectivityNodeInterface
{
    use IdentifiedObjectTrait;

    use IdentifiedObjectParentTrait;

    use BootSaveTrait;

    protected $bootFields = [['IdentifiedObject','identifiedobject','belongs','delete']];



    public function getTerminals(): array
    {
        $terminals = [];
        $founded = $this->hasMany(Terminal::class, 'connectivitynode_id');
        foreach ($founded->get() as $terminal) {
            $terminals[] = $terminal;
        }
        return $terminals;
    }

    //
}
