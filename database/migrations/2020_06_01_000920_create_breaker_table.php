<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreakerTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'breaker';

    /**
     * Run the migrations.
     * @table breaker
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('switchdrivemarkinfo_id');
            $table->unsignedBigInteger('identifiedobject_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('switchrelay_id');
            $table->unsignedBigInteger('drive_id');
            $table->double('FGC_BREAKERTIME')->nullable();
            $table->double('INTRANSITTIME')->nullable();
            $table->smallInteger('NORMALOPEN')->default('0');
            $table->smallInteger('OPEN_')->default('0');
            $table->smallInteger('RETAINED')->default('0');
            $table->integer('SWITCHONCOUNT')->nullable();
            $table->string('SWITCHONDATE', 255)->nullable();
            $table->double('BREAKINGCAPACITY')->nullable();
            $table->double('RATEDCURRENT')->nullable();
            $table->smallInteger('LOADBREAKSWITCH')->default('0');
            $table->string('FGC_JOININGNAME', 255)->nullable();
            $table->smallInteger('ROLLOUTTROLLEY')->nullable()->default('0');
            $table->integer('BREAKERKINDID')->nullable()->comment('Скорее все тут есть связь с таблицей');
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

            $table->foreign('switchrelay_id')
                ->references('id')->on('switchrelay')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('drive_id')
                ->references('id')->on('drive')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('switchdrivemarkinfo_id')
                ->references('id')->on('switchdrivemarkinfo')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Выключатели'");
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
