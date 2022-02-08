<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTowerinfoTable extends Migration
{

    protected $tableName = 'towerinfo';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('towermaterial_id')->nullable();
            $table->boolean('strut')->default(0);

            // связи
            $table->foreign('towermaterial_id')
                ->references('id')->on('towermaterial')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // на случай отката - удалить этот новый столбец
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('towermaterial_id');
        });
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('strut');
        });
    }
}
