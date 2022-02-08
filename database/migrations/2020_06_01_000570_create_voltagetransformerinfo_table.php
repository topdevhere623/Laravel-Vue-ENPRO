<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoltagetransformerinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'voltagetransformerinfo';

    /**
     * Run the migrations.
     * @table voltagetransformerinfo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('basevoltage_id')->nullable();
            $table->string('ASSETINFOKEY', 255)->nullable();
            $table->float('F')->nullable();
            $table->string('CXEMA_SOED', 255)->nullable();
            $table->string('RAZMER', 255)->nullable();
            $table->float('UMAX')->nullable();
            $table->float('UNOMV')->nullable();
            $table->float('UNOMNO')->nullable();
            $table->float('UNOMND')->nullable();
            $table->float('F_copy1')->nullable();
            $table->float('SNOM02')->nullable();
            $table->float('SNOM05')->nullable();
            $table->float('SNOM1')->nullable();
            $table->float('SNOM3')->nullable();
            $table->float('SNOMDVO')->nullable();
            $table->float('SMAX')->nullable();
            $table->float('KLASST')->nullable();
            $table->float('MASSA')->nullable();
            $table->string('MARKA', 255)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('basevoltage_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Информация о напряжениях трансформаторов'");
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
