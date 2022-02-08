<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'towerinfo';

    /**
     * Run the migrations.
     * @table substation_has_connector
     *
     * @return void
     */
    public function up()
    {
        // ts это новая таблица
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('towerkind_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->integer('wiren')->nullable();
            $table->string('img', 255)->nullable();
            $table->string('assetinfokey')->nullable();
            $table->string('speciestype')->nullable();
            $table->string('subclass')->nullable();
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('towerkind_id')
                ->references('id')->on('towerkind')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Марки опор'");
} catch (\Exception $e) {
}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // на случай отката - удалить таблицу
        Schema::dropIfExists($this->tableName);
    }
}
