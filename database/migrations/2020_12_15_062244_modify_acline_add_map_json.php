<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAclineAddMapjson extends Migration
{
    protected $tableName = 'acline';

    public function up()
    {
        try {
            Schema::table($this->tableName, function (Blueprint $table) {

                // вставить новый столбец
                $table->text('map_json')->nullable();
            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    // на случай отката
    public function down()
    {
        // удалить этот новый столбец
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('map_json');
        });
    }
}
