<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTowerTableA extends Migration
{

    protected $tableName = 'tower';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table($this->tableName, function (Blueprint $table) {

                // удалить старый столбец и связь
                if (DB::getDriverName() !== 'sqlite')$table->dropForeign('tower_substation_id_foreign');
                if (DB::getDriverName() !== 'sqlite')$table->dropColumn('substation_id');

            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        try {
            Schema::table($this->tableName, function (Blueprint $table) {

                // вставить новый столбец
                $table->unsignedBigInteger('connectivitycode_id')->nullable();
                $table->foreign('connectivitycode_id')
                    ->references('id')->on('connectivitycode')
                    ->onDelete('no action')
                    ->onUpdate('no action');
            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

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
            $table->dropColumn('connectivitycode_id');
        });
    }
}
