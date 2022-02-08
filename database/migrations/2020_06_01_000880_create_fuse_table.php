<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuseTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'fuse';

    /**
     * Run the migrations.
     * @table fuse
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
            $table->smallInteger('NORMALOPEN')->nullable();
            $table->smallInteger('OPEN_')->nullable();
            $table->smallInteger('RETAINED')->nullable();
            $table->integer('SWITCHONCOUNT')->nullable();
            $table->string('SWITCHONDATE', 255)->nullable();
            $table->integer('RATEDCURRENT')->nullable();
            $table->string('FGC_JOININGNAME', 255)->nullable();
            $table->float('ROLLOUTTROLLEY')->nullable();
            $table->string('TECHCHARACTERISTIC', 255)->nullable();
            $table->float('INOM')->nullable();
            $table->float('INOMOTKL')->nullable();
            $table->float('UMAX')->nullable();

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
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Предохранители'");
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
