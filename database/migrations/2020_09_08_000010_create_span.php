<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpan extends Migration
{
    public $tableName = 'span';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ts новая таблица
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('identifiedobject_id')->nullable();
            $table->unsignedBigInteger('aclinesegment_id')->nullable();
            $table->unsignedBigInteger('startIO_id')->nullable();
            $table->unsignedBigInteger('endIO_id')->nullable();
            $table->text('points')->nullable();
            $table->integer('spantype')->nullable();
            $table->integer('spanlength')->nullable();
            $table->integer('gabarit')->nullable();
            $table->text('photos')->nullable(); // фото
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('aclinesegment_id')
                ->references('id')->on('aclinesegment')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('startIO_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('endIO_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Пролеты'");
        } catch (Exception $e) {
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
