<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubstationHasConnectivityCodeTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'substation_has_connectivitycode';

    /**
     * Run the migrations.
     * @table substation_has_connectivitycode
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('substation_id');
            $table->unsignedBigInteger('connectivitycode_id');

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('substation_id')
                ->references('id')->on('substation')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('connectivitycode_id')
                ->references('id')->on('connectivitycode')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Коды подключений'");
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
