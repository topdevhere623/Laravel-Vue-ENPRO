<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnproTool extends Model
{
    protected $guarded = [];

    public function path()
    {
        return '/enprotools/' . $this->id;
    }
}
