<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisconnectorTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'disconnector';

    /**
     * Run the migrations.
     * @table disconnector
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('identifiedobject_id')->nullable();
            $table->unsignedBigInteger('startIO_id')->nullable();
            $table->unsignedBigInteger('span_id')->nullable();
            $table->smallInteger('type')->nullable(); // тип: 1- разрядник, 2 - ОПН
            $table->unsignedBigInteger('disconnectorinfo_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('insulatormark_id')->nullable();
            $table->unsignedBigInteger('drive_id')->nullable();
            $table->smallInteger('NORMALOPEN')->nullable();
            $table->smallInteger('OPEN_')->nullable();
            $table->smallInteger('RETAINED')->nullable();
            $table->integer('SWITCHONCOUNT')->nullable();
            $table->string('SWITCHONDATE', 255)->nullable();
            $table->integer('RATEDCURRENT')->nullable();
            $table->string('FGC_JOININGNAME', 255)->nullable();
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

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('startIO_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('span_id')
                ->references('id')->on('span')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('disconnectorinfo_id')
                ->references('id')->on('disconnectorinfo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('insulatormark_id')
                ->references('id')->on('insulatormark')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('drive_id')
                ->references('id')->on('drive')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Разьединители'");
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
