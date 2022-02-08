<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubstationTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'substation';

    /**
     * Run the migrations.
     * @table substation
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('substationfunction_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('substationinfo_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('address_id')->nullable(); // ts добавил nullable()
            $table->string('passport', 255)->nullable();
            $table->string('oilamount', 255)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('substationfunction_id')
                ->references('id')->on('substationfunction')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('substationinfo_id')
                ->references('id')->on('substationinfo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('address_id')
                ->references('id')->on('address')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'ТП'");
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
