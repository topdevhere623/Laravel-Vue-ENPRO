<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrossingTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'crossing';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // ts это новая таблица
            $table->bigIncrements('id');
            $table->unsignedBigInteger('identifiedobject_id')->nullable();
            $table->unsignedBigInteger('span_id')->nullable();
            $table->unsignedBigInteger('crossingtype_id')->nullable();
            $table->text('photos')->nullable(); // фото

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('span_id')
                ->references('id')->on('span')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('crossingtype_id')
                ->references('id')->on('crossingtype')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Пересечения местности'");
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
