<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSomeForeins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('busbarsection', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite')$table->dropForeign('busbarsection_bbsecinsulatorinfo_id_foreign');
                if (DB::getDriverName() !== 'sqlite')$table->dropForeign('busbarsection_bbsmaterial_id_foreign');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('busbarsection', function (Blueprint $table) {
            //
        });
    }
}
