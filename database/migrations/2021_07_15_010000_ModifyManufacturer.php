<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyManufacturer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('manufacturer', function (Blueprint $table) {

            $table->unsignedBigInteger('organisation_role_id')->nullable(true);

            $table->foreign('organisation_role_id')->on('organisation_role')->references('id');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
        Schema::table('manufacturer', function (Blueprint $table) {
            $table->dropForeign('organisation_role_organisation_role_id_foreign');

            $table->dropColumn('organisation_role_id');

        });

        }
    }

}
