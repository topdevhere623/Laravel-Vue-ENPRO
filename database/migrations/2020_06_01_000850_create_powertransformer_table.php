<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePowertransformerTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'powertransformer';

    /**
     * Run the migrations.
     * @table powertransformer
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('identifiedobject_id');
            $table->unsignedBigInteger('tapchangecontrol_id');
            $table->unsignedBigInteger('tankdesign_id');
            $table->unsignedBigInteger('thetmometeroil_id');
            $table->unsignedBigInteger('alarmdevice_id');
            $table->unsignedBigInteger('installation_id');
            $table->unsignedBigInteger('coolingsystem_id');
            $table->unsignedBigInteger('coolantmedia_id');
            $table->unsignedBigInteger('gasprotection_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('gasprotectionmark_id');
            $table->unsignedBigInteger('GASPROTECTIONOTHERMARK_ID');
            $table->integer('fgc_signphase')->nullable();
            $table->double('BEFORESHCIRCUITHIGHESTOPCURRENT')->nullable();
            $table->double('BEFORESHCIRCUITHIGHESTOPVOLTAGE')->nullable();
            $table->double('HIGHSIDEMINOPERATINGU')->nullable();
            $table->double('BEFORESHORTCIRCUITANGLEPF')->nullable();
            $table->integer('ISPARTOFGENERATORUNIT')->nullable();
            $table->integer('OPERATIONALVALUESCONSIDERED')->nullable();
            $table->string('VECTORGROUP', 255)->nullable();
            $table->string('TRANSFORMERFUNCTIONKINDKEY', 255)->nullable();
            $table->double('RATIOS')->nullable();
            $table->double('FREQ')->nullable();
            $table->string('INTERLACEPHASE', 255)->nullable();
            $table->string('TAPCHANGESTEPS', 255)->nullable();
            $table->string('MAGNETICCOREDESIGN', 255)->nullable();
            $table->string('powertransformercol', 255)->nullable();
            $table->string('HASTHERMOSIPHON', 255)->nullable();
            $table->string('powertransformercol1', 255)->nullable();
            $table->double('MASSA_POL_TR')->nullable();
            $table->double('MASSA_TRA_TR')->nullable();
            $table->double('MASSA_MAS_TR')->nullable();
            $table->double('MASSA_V_TR')->nullable();
            $table->double('MASSA_KOL_TR')->nullable();
            $table->integer('TAPNEUTRAL')->nullable();
            $table->integer('TAPCHANGESTEPSDOWN')->nullable();
            $table->integer('TAPWINDINGNO')->nullable();
            $table->double('TAPCHANGERSTEP')->nullable();
            $table->string('GASPROTECTION', 255)->nullable();
            $table->string('CONNECTIONGROUPHVLV', 255)->nullable();
            $table->string('CONNECTIONGROUPHVMV', 255)->nullable();
            $table->integer('WINDINGSNUM')->nullable();
            $table->string('WINDCONNECTDIAGRAM', 255)->nullable();
            $table->integer('TAPPOSITION')->nullable();
            $table->string('GASPROTECTIONOTHER', 255)->nullable();
            $table->string('R', 255)->nullable();
            $table->string('X', 255)->nullable();
            $table->string('PHASENO', 255)->nullable();
            $table->string('SPLITE', 255)->nullable();
            $table->string('IXX', 255)->nullable();
            $table->string('PXX', 255)->nullable();
            $table->string('PKZVN', 255)->nullable();
            $table->string('PKZVS', 255)->nullable();
            $table->string('PKZSN', 255)->nullable();
            $table->string('UKZVN', 255)->nullable();
            $table->string('UKZVS', 255)->nullable();
            $table->string('UKZSN', 255)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('tapchangecontrol_id')
                ->references('id')->on('tapchangecontrol')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('tankdesign_id')
                ->references('id')->on('tankdesign')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('thetmometeroil_id')
                ->references('id')->on('thetmometeroil')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('alarmdevice_id')
                ->references('id')->on('alarmdevice')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('installation_id')
                ->references('id')->on('installation')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('coolingsystem_id')
                ->references('id')->on('coolingsystem')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('coolantmedia_id')
                ->references('id')->on('coolantmedia')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('gasprotection_id')
                ->references('id')->on('gasprotection')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('gasprotectionmark_id')
                ->references('id')->on('gasprotectionmark')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('GASPROTECTIONOTHERMARK_ID')
                ->references('id')->on('gasprotectionmark')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Мощность трансформаторов'");
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
