<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroundingHasMeastestTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'grounding_has_meastest';

    /**
     * Run the migrations.
     * @table grounding_has_meastest
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('grounding_id');
            $table->unsignedBigInteger('meastest_id');

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('grounding_id')
                ->references('id')->on('grounding')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('meastest_id')
                ->references('id')->on('meastest')
                ->onDelete('no action')
                ->onUpdate('no action');
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
