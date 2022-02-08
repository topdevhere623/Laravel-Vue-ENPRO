<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCircuitbreakerinfoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'circuitbreakerinfo';

    /**
     * Run the migrations.
     * @table circuitbreakerinfo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->float('INOM')->nullable();
            $table->float('INKMN')->nullable();
            $table->float('INKMV')->nullable();
            $table->float('IUSTZKZN')->nullable();
            $table->float('IUSTZKZV')->nullable();
            $table->float('IUSTZPN')->nullable();
            $table->float('IUSTZPV')->nullable();
            $table->float('TUSTVN')->nullable();
            $table->float('TUSTVV')->nullable();
            $table->float('IKS380')->nullable();
            $table->float('IKS500')->nullable();
            $table->float('IKS660')->nullable();
            $table->float('IKSP220')->nullable();
            $table->float('IKSP400')->nullable();
            $table->float('IKNOMN')->nullable();
            $table->float('IKNOMV')->nullable();
            $table->float('IUSTKZN')->nullable();
            $table->float('IUSTKZV')->nullable();
            $table->float('IUSTER')->nullable();
            $table->float('IUSTTR')->nullable();
            $table->float('KI')->nullable();
            $table->float('NKC')->nullable();
            $table->float('NKCUI')->nullable();
            $table->float('NKCUIP')->nullable();
            $table->float('MASSA')->nullable();
            $table->string('ASSETINFOKEY', 255)->nullable();
            $table->unsignedBigInteger('basevoltage_id')->nullable();
            $table->string('mark', 255)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('basevoltage_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Информация по автоматическим выключателям'");
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
