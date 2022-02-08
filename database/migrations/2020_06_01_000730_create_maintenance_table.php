<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintenanceTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'maintenance';

    /**
     * Run the migrations.
     * @table maintenance
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->comment('Ремонты');
            $table->unsignedBigInteger('asset_id');
            $table->timestamp('maintdate')->nullable();
            $table->unsignedBigInteger('maint');
            $table->unsignedBigInteger('maintform');
            $table->unsignedBigInteger('material');
            $table->string('instalment', 255)->nullable();
            $table->string('comment', 255)->nullable();
            $table->string('wiremark', 255)->nullable();
            $table->double('cablerate')->nullable();
            $table->integer('mountedcableboxn')->nullable();
            $table->integer('remountedcableboxn')->nullable();
            $table->string('employee', 255)->nullable();
            $table->double('ccount')->nullable();
            $table->binary('executedwork')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('maint')
                ->references('id')->on('maint')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('maintform')
                ->references('id')->on('maintform')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('material')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Ремонтные работы'");
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
