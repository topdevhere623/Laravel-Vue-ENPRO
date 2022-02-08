<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnproVehicle extends Model
{
    protected $guarded = [];

    public function path()
    {
        return '/enprovehicles/' . $this->id;
    }

}
