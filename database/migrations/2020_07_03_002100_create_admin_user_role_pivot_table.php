<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserRolePivotTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'admin_user_role_pivots';

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
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('user_role_id')->unsigned()->default(0);

            // поля Laravel
            $table->timestamps();

            // связи
            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_role_id')
                ->references('id')->on('admin_user_roles')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Роли Пользователей (сводная)'");
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
