<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyIdentifiedobjectHasFile extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'identifiedobject_has_file';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('io_id')->nullable();
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('identifiedobject_has_file_file_id_foreign');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('io_id');
        });
    }
}
