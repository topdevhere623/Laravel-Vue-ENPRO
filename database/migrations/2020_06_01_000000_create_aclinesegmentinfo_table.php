<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclinesegmentinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'aclinesegmentinfo';

    /**
     * Run the migrations.
     * @table substation_has_connector
     *
     * @return void
     */
    public function up()
    {
        // ts это моя новая таблица
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('assetinfokey',255)->nullable();
            $table->integer('voltageid')->nullable();
            $table->string('subclass',255)->nullable();
            $table->float('unom')->nullable();
            $table->float('r')->nullable();
            $table->float('x')->nullable();
            $table->float('g')->nullable();
            $table->float('b')->nullable();
            $table->float('s')->nullable();
            $table->float('idd')->nullable();
            $table->float('df')->nullable();
            $table->float('dpkor')->nullable();
            $table->float('sf')->nullable();
            $table->integer('nf')->nullable();
            $table->integer('n')->nullable();
            $table->float('sst')->nullable();
            $table->boolean('status')->default(1); // ts я добавил

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Марки проводов'");
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
        // на случай отката - удалить таблицу
        Schema::dropIfExists($this->tableName);
    }
}
