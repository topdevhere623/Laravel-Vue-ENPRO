<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentFlowsTable extends Migration
{
    public $tableName = 'current_flows';
    use \App\Traits\CreateTableDataTypeTrait;

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('current_flows');
    }
}
