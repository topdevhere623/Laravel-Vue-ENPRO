<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetaccountingTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'assetaccounting';

    /**
     * Run the migrations.
     * @table assetaccounting
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('accountservice');
            $table->unsignedBigInteger('accountinterpriseserv');
            $table->unsignedBigInteger('accounttitledocument');
            $table->unsignedBigInteger('accountlandserv');
            $table->string('enterprisetitledocnum', 255)->nullable();
            $table->timestamp('validityperiod')->nullable();
            $table->timestamp('enterprisevalper')->nullable();
            $table->integer('landisregistered')->nullable();
            $table->string('landtitledoc', 255)->nullable();
            $table->timestamp('landvalper')->nullable();
            $table->integer('protectionzone')->nullable();
            $table->string('serviceboundaries', 255)->nullable();
            $table->string('titledocumentnum', 255)->nullable();
            $table->string('entcontractnumber', 255)->nullable();
            $table->string('enterpriseinvnum', 255)->nullable();
            $table->string('landcadastrnum', 255)->nullable();
            $table->string('landinventnum', 255)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('accountservice')
                ->references('id')->on('accountservice')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('accountinterpriseserv')
                ->references('id')->on('accountinterpriseserv')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('accounttitledocument')
                ->references('id')->on('accounttitledocument')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('accountlandserv')
                ->references('id')->on('accountlandserv')
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
