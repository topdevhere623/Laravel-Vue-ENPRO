<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDischargerinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'dischargerinfo';

    /**
     * Run the migrations.
     * @table dischargerinfo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('basevoltage_id')->nullable();
            $table->unsignedBigInteger('subclass_id')->nullable();
            $table->string('ASSETINFOKEY', 255)->nullable();
            $table->integer('subclass_classname_id')->nullable(); // ts добавил nullable()
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
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('basevoltage_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('subclass_id')
                ->references('id')->on('subclass')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Марки разрядников'");
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
