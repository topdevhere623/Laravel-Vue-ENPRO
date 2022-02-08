<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetKind extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_kind', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable(true);
            $table->string('ru_value')->nullable(true);
            $table->string('description')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('asset', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_kind_id')->nullable(true);
            $table->foreign('asset_kind_id')->on('asset_kind')->references('id');
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
            Schema::dropIfExists('asset_kind');
            Schema::table('asset', function (Blueprint $table) {
                $table->dropForeign('asset_asset_kind_id_foreign');
                $table->dropColumn('asset_kind_id');
            });
        }
    }

}
