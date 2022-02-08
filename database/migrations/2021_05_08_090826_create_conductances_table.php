<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConductancesTable extends Migration
{
    public $tableName = 'conductances';

    use \App\Traits\CreateTableDataTypeTrait;

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conductances');
    }
}
