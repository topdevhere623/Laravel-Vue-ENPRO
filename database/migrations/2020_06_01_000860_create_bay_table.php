<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBayTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'bay';

    /**
     * Run the migrations.
     * @table bay
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('voltagelevel_id');
            $table->unsignedBigInteger('bayinfo_id');
            $table->unsignedBigInteger('identifiedobject_id');
            $table->smallInteger('bayenergymeasflag')->nullable();
            $table->smallInteger('baypowermeasflag')->nullable();
            $table->string('breakerconfiguration', 255)->nullable();
            $table->string('busbarconfiguration', 255)->nullable();
            $table->integer('fgc_cellnumber')->nullable();
            $table->integer('operatingcurrent')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('voltagelevel_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('bayinfo_id')
                ->references('id')->on('bayinfo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment ''");
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
