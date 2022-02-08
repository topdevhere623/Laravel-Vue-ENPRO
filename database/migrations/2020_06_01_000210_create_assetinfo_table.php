<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'assetinfo';

    /**
     * Run the migrations.
     * @table assetinfo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment ''");
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
