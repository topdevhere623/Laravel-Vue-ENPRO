<?php

namespace App\Models;

use App\Contracts\CIM\Wires\EquipmentContainerInterface;
use App\Traits\ConnectivityNodeContainerTrait;
use App\Traits\EquipmentContainerTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentContainer extends  Model implements EquipmentContainerInterface
{
    use EquipmentContainerTrait;

    public $cnc;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (EquipmentContainer  $model) {
            $model->getConnectivityNodeContainer()->save();
            $model->connectivityNodeContainer()->associate($model->getConnectivityNodeContainer());
        });

        static::deleted(function (EquipmentContainer $model) {
            $model->getConnectivityNodeContainer()->delete();
        });

        static::saving(function (EquipmentContainer $model) {
            $model->getConnectivityNodeContainer()->save();
        });
    }

    public function getEquipmentContainer() : EquipmentContainer
    {
        return $this;
    }

}
