<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenttransformerTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'currenttransformer';

    /**
     * Run the migrations.
     * @table currenttransformer
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('asset_id');
            $table->unsignedBigInteger('identifiedobject_id');
            $table->double('ACCURACYLIMIT')->nullable();
            $table->double('COREBURDEN')->nullable();
            $table->string('CTCLASS', 255)->nullable();
            $table->integer('USAGE_')->nullable();
            $table->string('TERMINALKEY', 255)->nullable();
            $table->string('RATEDCURRENT', 255)->nullable();
            $table->string('ACCURACYCLASS', 255)->nullable();
            $table->integer('CELLNO')->nullable();
            $table->integer('COUNTNO')->nullable();
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
            $table->unsignedBigInteger('currenttransformerkind_id');

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('currenttransformerkind_id')
                ->references('id')->on('currenttransformerkind')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Трансформаторы'");
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
