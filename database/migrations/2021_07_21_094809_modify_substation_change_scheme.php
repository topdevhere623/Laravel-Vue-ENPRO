<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySubstationChangeScheme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('substation', 'scheme')) {
            Schema::table('substation', function (Blueprint $table) {
                $table->longText('scheme')->change();
            });
        } else {
            Schema::table('substation', function (Blueprint $table) {
                $table->longText('scheme')->nullable();
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
        Schema::table('scheme', function (Blueprint $table) {

            $table->dropColumn('scheme');

        });
    }
}
