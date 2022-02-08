<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasUsergroupTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'user_has_usergroup';

    /**
     * Run the migrations.
     * @table user_has_usergroup
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('user_id');
            $table->unsignedBigInteger('usergroup_id');
            $table->integer('usergroup_user_has_user_user_id');

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('usergroup_id')
                ->references('id')->on('usergroup')
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
