<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassnameTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'classname';

    /**
     * Run the migrations.
     * @table classname
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('name', 255)->nullable();
            $table->string('keylink', 255)->nullable(); // ts я добавил это поле, чтобы можно было импортировать с CLASSNAMEMARKTYPE
            $table->string('classname', 255)->nullable(); // ts я добавил это поле, чтобы можно было импортировать с CLASSNAMEMARKTYPE
            $table->string('marktypekey', 255)->nullable(); // ts я добавил это поле, чтобы можно было импортировать с CLASSNAMEMARKTYPE

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Имена классов'");
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
