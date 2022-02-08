<?php

namespace App\Models;

use App\Contracts\CIM\Wires\ConnectivityNodeContainerInterface;
use App\Traits\ConnectivityNodeContainerTrait;
use Illuminate\Database\Eloquent\Model;

class ConnectivityNodeContainer extends Model implements ConnectivityNodeContainerInterface
{
    use ConnectivityNodeContainerTrait;

    public $psr = null;


    protected static function boot()
    {
        parent::boot();

        static::creating(function (ConnectivityNodeContainer  $model) {
            $model->getPowerSystemResource()->save();
            $model->powersystemresource()->associate($model->getPowerSystemResource());
        });

        static::deleted(function (ConnectivityNodeContainer $model) {
            $model->getPowerSystemResource()->delete();
        });

        static::saving(function (ConnectivityNodeContainer $model) {
            $model->getPowerSystemResource()->save();
            $model->powersystemresource()->associate($model->getPowerSystemResource());
        });
    }
    public function getConnectivityNodeContainer()
    {
        return $this;
    }
    //
}
