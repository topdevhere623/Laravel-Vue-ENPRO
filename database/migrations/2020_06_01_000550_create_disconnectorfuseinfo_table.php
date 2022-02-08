<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisconnectorfuseinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'disconnectorfuseinfo';

    /**
     * Run the migrations.
     * @table  disconnectorfuseinfo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('ASSETINFOKEY', 255)->nullable();
            $table->float('UNOM')->nullable();
            $table->float('INOM')->nullable();
            $table->float('IPIKST')->nullable();
            $table->float('TTERMSTGL')->nullable();
            $table->float('TYPEPRIVGL')->nullable();
            $table->float('VOLTAGEID')->nullable();
            $table->float('UMAX')->nullable();
            $table->float('ITERMSTGL')->nullable();
            $table->float('ISKVZAZ')->nullable();
            $table->float('ITERMSTZAZ')->nullable();
            $table->float('TTERMSTZAZ')->nullable();
            $table->float('MASSA')->nullable();
            $table->string('DRIVEMARK', 255)->nullable();
            $table->unsignedBigInteger('drive_id')->nullable();
            $table->string('REMARK', 255)->nullable();
            $table->float('INOMOTKL')->nullable();
            $table->unsignedBigInteger('insulatormark_id')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('drive_id')
                ->references('id')->on('drive')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('insulatormark_id')
                ->references('id')->on('insulatormark')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Информация о разьединителях-предохранителях'");
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
