<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSomeForeigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('connectivitycode', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite')$table->dropForeign('connectivitycode_terminal_id_foreign');
            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        try {
            Schema::table('connectivitycode', function (Blueprint $table) {
                $table->dropColumn('terminal_id');
            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connectivitycode', function (Blueprint $table) {
            //
        });
    }

}
