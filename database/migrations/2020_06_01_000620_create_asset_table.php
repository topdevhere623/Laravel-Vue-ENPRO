<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'asset';

    /**
     * Run the migrations.
     * @table asset
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('gost_id')->nullable(); // ts добавил nullable
            $table->unsignedBigInteger('manufacturer_id')->nullable(); // ts добавил nullable
            $table->string('keylink', 255)->nullable();
            $table->double('initiallossoflife')->nullable();
            $table->string('corporatecode', 255)->nullable();
            $table->timestamp('installationdate')->nullable();
            $table->timestamp('manufactureddate')->nullable();
            $table->string('serialnumber', 255)->nullable();
            $table->string('inventorynumber', 255)->nullable();
            $table->integer('initialcondition')->nullable();
            $table->timestamp('purchasedate')->nullable();
            $table->double('purchaseprice')->nullable();
            $table->timestamp('receiveddate')->nullable();
            $table->timestamp('retireddate')->nullable();
            $table->string('orgmanagerkey', 255)->nullable();
            $table->string('fgc_parentkey', 255)->nullable();
            $table->string('orgassetownerkey', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('assetinfokey', 255)->nullable();
            $table->string('manufactureddt', 255)->nullable();
            $table->string('assetcol', 255)->nullable();
            $table->timestamp('deliverydate')->nullable();
            $table->integer('ownereqassetid')->nullable();
            $table->string('comment', 255)->nullable();
            $table->integer('critical')->nullable();
            $table->string('cadastralnumber', 255)->nullable();
            $table->string('manufacturer', 255)->nullable();
            $table->integer('warehouse')->nullable();
            $table->string('inventorynumbermp', 255)->nullable();
            $table->string('inventorynumberbp', 255)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('gost_id')
                ->references('id')->on('gost')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('manufacturer_id')
                ->references('id')->on('manufacturer')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Общие данные'");
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
