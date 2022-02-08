<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyConnectivitycodeTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'connectivitycode';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {

            // таблицу substation связать с таблицами identifiedobject  ( так же как например таблица breaker)
            $table->unsignedBigInteger('terminal_id')->nullable();

            $table->foreign('terminal_id')
                ->references('id')->on('terminal')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        try {
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->dropColumn('terminal_id');
            });
        } catch (Exception $e) {
        }
    }
}
