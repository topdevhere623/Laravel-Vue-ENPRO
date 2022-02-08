<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenttransformerinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'currenttransformerinfo';

    /**
     * Run the migrations.
     * @table currenttransformerinfo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('ASSETINFOKEY', 255)->nullable();
            $table->unsignedBigInteger('basevoltage_id')->nullable();
            $table->float('UMAX')->nullable();
            $table->float('INOM1')->nullable();
            $table->float('INOM2')->nullable();
            $table->float('IELST')->nullable();
            $table->float('F')->nullable();
            $table->float('SNOM_Z')->nullable();
            $table->float('IPRKRO_Z')->nullable();
            $table->float('IKRT_1')->nullable();
            $table->float('IKRT_3')->nullable();
            $table->float('IKRELST')->nullable();
            $table->float('UISP_1')->nullable();
            $table->float('UISP_GI')->nullable();
            $table->float('IPRKRO_15')->nullable();
            $table->float('UNOM_DO')->nullable();
            $table->float('ITERM_1')->nullable();
            $table->float('ITERM_3')->nullable();
            $table->float('MASSA')->nullable();
            $table->string('N_VO', 255)->nullable();
            $table->string('KLASST_Z', 255)->nullable();
            $table->string('KLASST_IZ', 255)->nullable();
            $table->string('SNOM_IZ', 255)->nullable();
            $table->float('UNOM')->nullable();

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
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Информация о трансформаторах'");
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
