<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatioTapChangerTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratio_tap_changer_tables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('identifiedobject_id');
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->unsignedBigInteger('tapchangertablepoint');
            $table->foreign('tapchangertablepoint')
                ->references('id')->on('tap_changer_table_points')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratio_tap_changer_tables');
    }
}
