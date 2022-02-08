<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySubstationTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'substation';

    /**
     * Run the migrations.
     * @table user
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {

            // таблицу substation связать с таблицами identifiedobject и assets ( так же как например таблица breaker)
            $table->unsignedBigInteger('identifiedobject_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('asset_id')->nullable(); // ts добавил nullable()
            $table->text('photos')->nullable(); // фото
            $table->boolean('status')->default(1);

            // ts cascade добавил
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
