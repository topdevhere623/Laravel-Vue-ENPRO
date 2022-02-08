<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreakerinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'breakerinfo';

    /**
     * Run the migrations.
     * @table breakerinfo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('ASSETINFOKEY', 255)->nullable();
            $table->string('KIND', 255)->nullable();
            $table->string('PRIVOD', 255)->nullable();
            $table->unsignedBigInteger('basevoltage_id')->nullable();
            $table->unsignedBigInteger('subclass_id')->nullable();
            $table->integer('subclass_classname_id')->nullable(); // ts добавил nullable()
            $table->float('UNOM')->nullable();
            $table->float('UMAX')->nullable();
            $table->float('INOM')->nullable();
            $table->float('INOMOTKL')->nullable();
            $table->float('IPIKST')->nullable();
            $table->float('IMAXSKV')->nullable();
            $table->float('IVKLMAX')->nullable();
            $table->float('IVKLD')->nullable();
            $table->float('ITERMST')->nullable();
            $table->float('TTERM')->nullable();
            $table->float('TOTKLPOLN')->nullable();
            $table->float('TOTKLSOBST')->nullable();
            $table->float('TVKLSOBST')->nullable();
            $table->float('TMINPAUSE')->nullable();
            $table->float('MASSA')->nullable();
            $table->float('MASSAIZOL')->nullable();
            $table->string('DRIVEMARK', 255)->nullable();
            $table->string('REMARK', 255)->nullable();
            $table->unsignedBigInteger('drive_id')->nullable();

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

            $table->foreign('drive_id')
                ->references('id')->on('drive')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Информация о выключателях'");
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
