<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePowertransformerinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'powertransformerinfo';

    /**
     * Run the migrations.
     * @table powertransformerinfo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('basevoltage_id')->nullable();
            $table->unsignedBigInteger('subclass_id')->nullable();
            $table->string('ASSETINFOKEY', 255)->nullable();
            $table->string('TYPE', 255)->nullable();
            $table->string('MANUFACTURER', 255)->nullable();
            $table->double('UNOM')->nullable();
            $table->double('RATIOS')->nullable();
            $table->double('TAPCHANGESTEPS')->nullable();
            $table->double('R')->nullable();
            $table->double('X')->nullable();
            $table->string('WINDCONNECTDIAGRAM', 255)->nullable();
            $table->double('INOMSN')->nullable();
            $table->double('MASSA_KOL_TR')->nullable();
            $table->double('UKZSN')->nullable();
            $table->double('PXX')->nullable();
            $table->double('PKZVS')->nullable();
            $table->double('PKZSN')->nullable();
            $table->integer('subclass_classname_id');
            $table->integer('WINDINGSNUM')->nullable();
            $table->integer('PHASENO')->nullable();
            $table->integer('SPLITE')->nullable();
            $table->double('UNOMVN')->nullable();
            $table->double('UNOMSN')->nullable();
            $table->double('TAPCHANGERSTEP')->nullable();
            $table->double('IXX')->nullable();
            $table->double('INOMNN')->nullable();
            $table->double('INOMVN')->nullable();
            $table->double('MASSA_POL_TR')->nullable();
            $table->double('MASSA_TRA_TR')->nullable();
            $table->double('MASSA_MAS_TR')->nullable();
            $table->double('MASSA_V_TR')->nullable();
            $table->double('UKZVN')->nullable();
            $table->double('UKZVS')->nullable();
            $table->double('PKZVN')->nullable();
            $table->double('UNOMNN')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('basevoltage_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('subclass_id')
                ->references('id')->on('subclass')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Мощность трансформаторов'");
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
