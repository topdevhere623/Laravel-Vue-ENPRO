<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnproForceTable extends Migration
{
    public $tableName = 'enpro_force';
    use \App\Traits\CreateTableDataTypeTrait;

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enpro_force');
    }
}
