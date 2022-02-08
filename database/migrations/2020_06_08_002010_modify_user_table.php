<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'user';

    /**
     * Run the migrations.
     * @table user
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {

            // ts для логина и ролей нужно + аватарка на будующее и сортировка
            $table->string('email', 255)->default(''); //->unique(); // убрал из-за ошибки при импорте на хостинге
            $table->timestamp('email_verified_at')->nullable();
            $table->string('img', 255)->nullable();
            $table->smallInteger('sort')->default(0);
            $table->rememberToken();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // на случай отката - удалить этот новый столбец
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
}
