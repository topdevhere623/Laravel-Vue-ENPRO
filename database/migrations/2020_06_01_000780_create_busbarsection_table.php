<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusbarsectionTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'busbarsection';

    /**
     * Run the migrations.
     * @table busbarsection
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('identifiedobject_id');
            $table->unsignedBigInteger('bbsecinsulatorinfo_id');
            $table->unsignedBigInteger('bbsmaterial_id');
            $table->double('ipmax')->nullable();
            $table->integer('INSULATORCOUNT')->nullable();
            $table->float('LENGTH')->nullable();
            $table->string('CROSSSECTION', 255)->nullable();
            $table->integer('BBSECKIND')->nullable();
            $table->string('busbarsectioncol', 255)->nullable();
            $table->string('BBSSHAPE', 255)->nullable();
            $table->float('BBSWIDTH')->nullable();
            $table->float('BBSHIGHT')->nullable();
            $table->float('IDOP1')->nullable();
            $table->float('IDOP2')->nullable();
            $table->float('IDOP3')->nullable();
            $table->float('IDOP4')->nullable();
            $table->float('MP')->nullable();
            $table->integer('PAINTED')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('bbsecinsulatorinfo_id')
                ->references('id')->on('bbsecinsulatorinfo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('bbsmaterial_id')
                ->references('id')->on('bbsmaterial')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Секция шин'");
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
