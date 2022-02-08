<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusceptancesTable extends Migration
{
    public $tableName = 'susceptances';

    use \App\Traits\CreateTableDataTypeTrait;


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('susceptances');
    }
}
