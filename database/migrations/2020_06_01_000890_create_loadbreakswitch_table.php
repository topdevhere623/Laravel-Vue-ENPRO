<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadbreakswitchTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'loadbreakswitch';

    /**
     * Run the migrations.
     * @table loadbreakswitch
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('identifiedobject_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('switchdrive_id');
            $table->unsignedBigInteger('switchdrivemarkinfo_id');
            $table->unsignedBigInteger('drive_id');
            $table->float('FGC_BREAKERTIME')->nullable();
            $table->float('INTRANSITTIME')->nullable();
            $table->integer('NORMALOPEN')->nullable();
            $table->integer('OPEN_')->nullable();
            $table->integer('RETAINED')->nullable();
            $table->integer('SWITCHONCOUNT')->nullable();
            $table->string('SWITCHONDATE', 255)->nullable();
            $table->float('BREAKINGCAPACITY')->nullable();
            $table->float('RATEDCURRENT')->nullable();
            $table->integer('LOADBREAKSWITCH')->nullable();
            $table->string('FGC_JOININGNAME', 255)->nullable();
            $table->integer('ROLLOUTTROLLEY')->nullable();
            $table->integer('BREAKERKINDID')->nullable();
            $table->integer('WORKINGLIFE')->nullable();
            $table->string('DRIVEMARK', 255)->nullable();
            $table->float('INOM')->nullable();
            $table->float('INOMOTKL')->nullable();
            $table->float('IPIKST')->nullable();
            $table->float('IMAXSKV')->nullable();
            $table->float('IVKLMAX')->nullable();
            $table->float('IVKLD')->nullable();
            $table->float('ITERMST')->nullable();
            $table->float('TTERM')->nullable();
            $table->float('TOTKLPOLN')->nullable();
            $table->float('TOTKLSOBST')->nullable();
            $table->float('TVKLSOBST')->nullable();
            $table->float('TMINPAUSE')->nullable();
            $table->float('MASSA')->nullable();
            $table->float('MASSAIZOL')->nullable();
            $table->float('UMAX')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('switchdrive_id')
                ->references('id')->on('switchdrive')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('switchdrivemarkinfo_id')
                ->references('id')->on('switchdrivemarkinfo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('drive_id')
                ->references('id')->on('drive')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Выключатели нагрузки'");
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
