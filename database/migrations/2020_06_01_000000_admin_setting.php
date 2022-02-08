<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminSetting extends Migration
{
    public $tableName = 'admin_settings';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ts новая таблица
        Schema::create($this->tableName, function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('key'); //->unique(); убрал из-за ошибки при импорте на хостинге
            $table->text('value');
            $table->string('comment')->nullable();
            $table->smallInteger('sort')->default(0);
            $table->timestamps();
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Настройка Админки'");
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
