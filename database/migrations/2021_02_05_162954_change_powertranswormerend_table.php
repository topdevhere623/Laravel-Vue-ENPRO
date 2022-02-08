<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePowertranswormerendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('powertransformerend', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite')$table->dropForeign('basevoltage_basevoltage_id_foreign');
                if (DB::getDriverName() !== 'sqlite')$table->dropColumn('basevoltage_id');
            });
        } catch (\Exception $e) {

        }
        try {
            Schema::table('powertransformerend', function (Blueprint $table) {
                //
                $table->unsignedBigInteger('transformer_ends_id')->nullable();
                $table->foreign('transformer_ends_id')
                    ->references('id')->on('transformer_ends')
                    ->ondelete('cascade')
                    ->onupdate('cascade');

                $table->float('b')->default(0.0);
                $table->float('b0')->default(0.0);
                $table->float('g')->default(0.0);
                $table->float('g0')->default(0.0);

                $table->integer('phaseangleclock')->default(0);

                $table->unsignedBigInteger('connectionkind')->nullable();
                $table->foreign('connectionkind')
                    ->references('id')->on('winding_connections')
                    ->ondelete('cascade')
                    ->onupdate('cascade');

                $table->float('rateds')->default(0.0);
                $table->float('ratedu')->default(0.0);

                $table->float('x')->default(0.0);
                $table->float('x0')->default(0.0);
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('powertransformerend', function (Blueprint $table) {
            //
        });
    }
}
