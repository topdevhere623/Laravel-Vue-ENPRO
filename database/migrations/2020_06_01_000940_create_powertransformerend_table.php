<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePowertransformerendTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'powertransformerend';

    /**
     * Run the migrations.
     * @table powertransformerend
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->integer('endnumber')->nullable();
            $table->unsignedBigInteger('powertransformer_id')->nullable();
            $table->unsignedBigInteger('basevoltage_id')->nullable();
            $table->double('DVOLTAGE_NN1')->nullable();
            $table->double('DVOLTAGE_NN2')->nullable();
            $table->double('DVOLTAGE_NN3')->nullable();
            $table->double('DVOLTAGE_NN4')->nullable();
            $table->double('DVOLTAGE_NN5')->nullable();
            $table->double('RATEI')->nullable();
            $table->unsignedBigInteger('insulatormark_id')->nullable();
            $table->integer('INSULATORNO')->nullable();
            $table->integer('HAVEZERO')->nullable();
            $table->unsignedBigInteger('insulator_id')->nullable();
            $table->string('WINDINGCONNECTION', 255)->nullable();
            $table->float('UNOM')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('powertransformer_id')
                ->references('id')->on('powertransformer')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('basevoltage_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('insulatormark_id')
                ->references('id')->on('insulatormark')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('insulator_id')
                ->references('id')->on('insulator')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Мощность трансформаторов'");
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
