<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTower extends Migration
{
    public $tableName = 'tower';

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
            $table->unsignedBigInteger('towermaterial_id')->nullable(); // материалы опор
            $table->unsignedBigInteger('towerkind_id')->nullable(); // назначение опор
            $table->unsignedBigInteger('towerconstructionkind_id')->nullable(); // конструкция опор
            $table->unsignedBigInteger('towerinfo_id')->nullable(); // марки опор
            $table->boolean('fict_tp')->default(0)->nullable(); // признак фиктивной ТП
            $table->integer('propn')->nullable(); // кол-во стоек
            $table->string('guy', 50)->nullable();; // оттяжка
            $table->integer('strutn')->nullable();; // подкос
            $table->string('strut', 50)->nullable();; // подкос
            $table->string('annex', 50)->nullable();; // приставка
            $table->boolean('eqgrounding')->nullable();; // заземление
            $table->boolean('eqotherline')->nullable();; // линии разных классов напряжения
            $table->boolean('eqcommline')->nullable();; // линии связи
            $table->boolean('eqlamp')->nullable();; // фонарь
            $table->boolean('eqadapter')->nullable();; // адаптер
            $table->boolean('eqaccident')->nullable();; // аварийная
            $table->boolean('eqnoup')->nullable();; // подьем запрещен
            $table->boolean('status')->default(1);
            $table->text('photos')->nullable(); // фото

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('towermaterial_id')
                ->references('id')->on('towermaterial')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('towerkind_id')
                ->references('id')->on('towerkind')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('towerconstructionkind_id')
                ->references('id')->on('towerconstructionkind')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('towerinfo_id')
                ->references('id')->on('towerinfo')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Опоры'");
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
