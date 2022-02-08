<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectorTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'connector';

    /**
     * Run the migrations.
     * @table connector
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            //$table->unsignedBigInteger('basevoltage_id'); // ts убрал это поле, т.к. через IO можно узнать базвое напряжение
            $table->string('keylink', 255)->nullable();
            $table->integer('connected')->nullable()->default('1');

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
//            $table->foreign('basevoltage_id')
//                ->references('id')->on('basevoltage')
//                ->onDelete('no action')
//                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Фидеры'");
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
        Schema::dropIfExists($this->tableName);
    }
}
