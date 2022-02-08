<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuldingpartTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'buldingpart';

    /**
     * Run the migrations.
     * @table buldingpart
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('uid');
            $table->unsignedBigInteger('wallmaterial');
            $table->unsignedBigInteger('floormaterial');
            $table->unsignedBigInteger('overlapsmaterial');
            $table->unsignedBigInteger('doormaterial');
            $table->unsignedBigInteger('partitionsmaterial');
            $table->unsignedBigInteger('foundationmaterial');
            $table->unsignedBigInteger('chanoverlapsmaterial');
            $table->unsignedBigInteger('tpsupportkind');
            $table->double('cdlength')->nullable();
            $table->double('cdwidth')->nullable();
            $table->double('cdheight')->nullable();
            $table->double('cdarea')->nullable();
            $table->integer('blindcount')->nullable();
            $table->double('blindarea')->nullable();
            $table->double('tpsupportcount')->nullable();
            $table->double('tpsupsiteheight')->nullable();
            $table->double('tpsupsitesize')->nullable();
            $table->double('tpsupsitelength')->nullable();
            $table->double('tpsupsitewidth')->nullable();
            $table->integer('entriescounthv_l')->nullable();
            $table->integer('entriescounthv_c')->nullable();
            $table->integer('entriescountlv_l')->nullable();
            $table->integer('entriescountlv_c')->nullable();
            $table->integer('transcellcount')->nullable();
            $table->integer('linecellcount')->nullable();
            $table->integer('workcellcount')->nullable();
            $table->integer('reservecellcount')->nullable();
            $table->integer('feederscounthv_l')->nullable();
            $table->integer('feederscounthv_c')->nullable();
            $table->unsignedBigInteger('blindarea_uid');
            $table->unsignedBigInteger('fencematerial');

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('wallmaterial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('floormaterial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('overlapsmaterial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('doormaterial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('partitionsmaterial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('foundationmaterial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('chanoverlapsmaterial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('tpsupportkind')
                ->references('id')->on('tpsupportkind')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('blindarea_uid')
                ->references('uid')->on('blindarea')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('fencematerial')
                ->references('uid')->on('material')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Строительные части'");
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
