<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyConnectorTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'connector';

    /**
     * Run the migrations.
     * @table user
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {

            // таблицу connector связать с таблицами identifiedobject и assets (из чата Телеграмм, как breaker)
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('identifiedobject_id')->nullable();

            // ts добавить поле только для одного изображения img - схемы-фидера
            $table->string('img',255)->nullable();

            // связи // ts cascade добавид
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // ts cascade добавил
            $table->foreign('asset_id')
                ->references('id')->on('asset')
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
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('asset_id');
        });
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('identifiedobject_id');
        });
    }
}
