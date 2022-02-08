<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisconnectorfuseTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'disconnectorfuse';

    /**
     * Run the migrations.
     * @table disconnectorfuse
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('identifiedobject_id');
            $table->unsignedBigInteger('drive_id');
            $table->smallInteger('NORMALOPEN')->nullable();
            $table->smallInteger('OPEN_')->nullable();
            $table->smallInteger('RETAINED')->nullable();
            $table->string('WORKINGLIFE', 255)->nullable();
            $table->string('TECHCHARACTERISTIC', 255)->nullable();
            $table->string('DRIVEMARK', 255)->nullable();
            $table->float('INOM')->nullable();
            $table->float('IPIKST')->nullable();
            $table->float('ITERMSTGL')->nullable();
            $table->float('TTERMSTGL')->nullable();
            $table->float('ISKVZAZ')->nullable();
            $table->float('ITERMSTZAZ')->nullable();
            $table->float('TTERMSTZAZ')->nullable();
            $table->float('MASSA')->nullable();
            $table->float('UMAX')->nullable();
            $table->float('INOMOTKL')->nullable();

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

            $table->foreign('drive_id')
                ->references('id')->on('drive')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Разьединители-предохранители'");
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
