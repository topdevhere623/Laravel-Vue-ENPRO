<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDischargerTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'discharger';

    /**
     * Run the migrations.
     * @table discharger
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('identifiedobject_id')->nullable();
            $table->unsignedBigInteger('startIO_id')->nullable();
            $table->unsignedBigInteger('span_id')->nullable();
            $table->smallInteger('type')->nullable(); // тип: 1 - разьединитель, 2 - реклоузер, 3- выключатель нагрузки
            $table->unsignedBigInteger('dischargerinfo_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('drive_id')->nullable();
            $table->unsignedBigInteger('switchdrivemarkinfo_id')->nullable();
            $table->unsignedBigInteger('insulatormark_id')->nullable();
            $table->string('TECHCHARACTERISTIC', 255)->nullable();
            $table->integer('DISCOUNT')->nullable();
            $table->float('MAXOPERVOLT')->nullable();
            $table->float('DRIVEMARK')->nullable();
            $table->float('UMAX')->nullable();
            $table->float('UPRMIN')->nullable();
            $table->float('UPRMAX')->nullable();
            $table->float('UPRMINS')->nullable();
            $table->float('UPRMIND')->nullable();
            $table->float('UVMINS')->nullable();
            $table->float('UVMIND')->nullable();
            $table->float('UPRIM')->nullable();
            $table->float('UOST3000')->nullable();
            $table->float('UOST5000')->nullable();
            $table->float('UOST10000')->nullable();
            $table->float('UISP')->nullable();
            $table->float('IUT')->nullable();
            $table->float('IPS16_40')->nullable();
            $table->float('IPS2000')->nullable();
            $table->float('IOTKLMIN')->nullable();
            $table->float('IOTKLMAX')->nullable();
            $table->float('LIPV')->nullable();
            $table->float('LIPVV')->nullable();
            $table->float('IVIM')->nullable();
            $table->float('LLENGTH')->nullable();
            $table->float('LUT')->nullable();
            $table->float('TDOP')->nullable();
            $table->float('HHIGHT')->nullable();
            $table->float('MASSA')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('startIO_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('span_id')
                ->references('id')->on('span')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('dischargerinfo_id')
                ->references('id')->on('dischargerinfo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('asset_id')
                ->references('id')->on('asset')
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

            $table->foreign('insulatormark_id')
                ->references('id')->on('insulatormark')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Разрядники'");
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
