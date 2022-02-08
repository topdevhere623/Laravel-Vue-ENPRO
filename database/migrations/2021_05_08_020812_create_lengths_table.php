<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLengthsTable extends Migration
{
    public $tableName = 'lengths';

    use \App\Traits\CreateTableDataTypeTrait;


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lengths');
    }
}
