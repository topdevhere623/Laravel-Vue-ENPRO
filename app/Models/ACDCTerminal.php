<?php

namespace App\Models;

use App\Contracts\CIM\Wires\ACDCTerminalInterface;
use App\Traits\ACDCTerminalTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ACDCTerminal extends Model implements ACDCTerminalInterface
{
    use ACDCTerminalTrait;
    public $io = null;

    public function getACDCTerminal() : ACDCTerminal
    {
        return $this;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (ACDCTerminal  $model) {
            if($model->getIdentifiedObject()) {
                $model->getIdentifiedObject()->save();
                $model->identifiedobjectBelong()->associate($model->getIdentifiedObject());
            };
        });

        static::deleted(function (ACDCTerminal $model) {
            $model->getIdentifiedObject()->delete();
        });

        static::saving(function (ACDCTerminal $model) {
            $model->getIdentifiedObject()->save();
        });
    }

}
