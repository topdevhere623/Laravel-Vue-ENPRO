<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransformerEndsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transformer_ends', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('identifiedobject_id');
            $table->unsignedBigInteger('basevoltage_id')->nullable();
            $table->unsignedBigInteger('terminal_id')->nullable();

            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('no action')
                ->onUpdate('no action');
            $table->double('bmag_sat')->default(0.0);
            $table->integer('end_number')->default(0);;
            $table->boolean('grounded')->default(false);
            $table->double('mag_base_u')->default(0.0);
            $table->double('mag_sat_flux')->default(0.0);
            $table->double('rground')->default(0.0);
            $table->double('xground')->default(0.0);

            $table->foreign('basevoltage_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('terminal_id')
                ->references('id')->on('terminal')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('phasetapchanger')->nullable();

            $table->foreign('phasetapchanger')
                ->references('id')->on('phase_tap_changers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('ratiotapchanger')->nullable();
            $table->foreign('ratiotapchanger')
                ->references('id')->on('ratio_tap_changers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('starimpedance')->nullable();
            $table->foreign('starimpedance')
                ->references('id')->on('transformer_star_impedances')
                ->ondelete('cascade')
                ->onupdate('cascade');

            $table->unsignedBigInteger('coreadmittance')->nullable();
            $table->foreign('coreadmittance')
                ->references('id')->on('transformer_core_admittances')
                ->ondelete('cascade')
                ->onupdate('cascade');






        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transformer_ends');
    }
}
