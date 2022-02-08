<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserRoleTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'admin_user_roles';

    /**
     * Run the migrations.
     * @table user
     *
     * @return void
     */
    public function up()
    {
        // ts новая таблица
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('name'); //->unique(); // убрал из-за ошибки при импорте на хостинге
            $table->string('comment')->nullable();
            $table->boolean('import')->default(0);
            $table->boolean('api')->default(0);
            $table->boolean('tasks')->default(0);
            $table->boolean('spravs')->default(0);
            $table->boolean('settings')->default(0);
            $table->boolean('users')->default(0);
            $table->smallInteger('sort')->default(0);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Роли Пользователей'");
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
