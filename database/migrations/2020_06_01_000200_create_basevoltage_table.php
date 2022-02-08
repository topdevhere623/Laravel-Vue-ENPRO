<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseVoltageTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'basevoltage';

    /**
     * Run the migrations.
     * @table basevoltage
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('keylink', 255); // KEYLINK
            $table->double('nominalvoltage')->default('0.0000'); // NOMINALVOLTAGE
            $table->string('voltagecode', 255)->nullable(); // VOLTAGECODE
            $table->integer('kind')->nullable(); // KIND
            $table->string('name', 255)->nullable(); // ts в нижний регистр все поля перевел - NAME
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Базовые напряжения'");
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
