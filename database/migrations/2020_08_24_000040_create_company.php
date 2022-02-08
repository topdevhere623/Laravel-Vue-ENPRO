<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompany extends Migration
{
    public $tableName = 'companies';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ts новая таблица
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->text('coordinates')->nullable(); // json
            $table->string('email', 255)->nullable();
            $table->string('web', 50)->nullable();
            $table->string('img')->nullable();
            $table->smallInteger('sort')->default(0);
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Организации'");
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
