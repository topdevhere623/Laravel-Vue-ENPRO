<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeastestTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'meastest';

    /**
     * Run the migrations.
     * @table meastest
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_id');
            $table->timestamp('creationdate')->nullable();
            $table->string('protocolnum', 255)->nullable();
            $table->integer('kind')->nullable();
            $table->string('equipmentname', 255)->nullable();
            $table->integer('cellnum')->nullable()->comment('Номер ячейки');
            $table->string('employee', 255)->nullable();
            $table->string('comment', 255)->nullable();
            $table->string('conclusion', 255)->nullable();
            $table->string('measvalue', 255)->nullable();
            $table->double('meastime')->nullable();
            $table->double('leakagea')->nullable();
            $table->double('leakageb')->nullable();
            $table->double('leakagec')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment ''");
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
