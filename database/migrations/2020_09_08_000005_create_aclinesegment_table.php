<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclinesegmentTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'aclinesegment';

    /**
     * Run the migrations.
     * @table substation_has_connector
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // ts это новая таблица
            $table->bigIncrements('id');
            $table->unsignedBigInteger('identifiedobject_id')->nullable();
            $table->unsignedBigInteger('acline_id')->nullable();
            $table->unsignedBigInteger('wiremark_id')->nullable();
            $table->unsignedBigInteger('layingcondition_id')->nullable();
            $table->integer('wires')->nullable();
            $table->integer('wiren')->nullable();
            $table->integer('wirelength')->nullable();
            $table->integer('wirephasen')->nullable();
            $table->integer('cabelsn')->nullable();
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('acline_id')
                ->references('id')->on('acline')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('wiremark_id')
                ->references('id')->on('aclinesegmentinfo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('layingcondition_id')
                ->references('id')->on('layingconditionkind')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Сегменты линий'");
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
