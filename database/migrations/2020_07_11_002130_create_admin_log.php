<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLog extends Migration
{
    public $tableName = 'admin_log';

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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->dateTime('time')->nullable();
            $table->double('duration', 8, 3)->nullable();
            $table->string('ip', 100)->nullable();
            $table->string('method', 10)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('input', 255)->nullable();
            $table->string('browser', 255)->nullable();

            // связи
            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Журнал операций Пользователя'");
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
