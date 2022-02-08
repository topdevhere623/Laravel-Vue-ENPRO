<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectivityNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('connectivity_nodes', function (Blueprint $table) {
                $table->id();
                $table->timestamps();

                $table->unsignedBigInteger('identifiedobject_id');
                $table->foreign('identifiedobject_id')
                    ->references('id')->on('identifiedobject')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->unsignedBigInteger('connectivitynodecontainer_id')->nullable();
                $table->foreign('connectivitynodecontainer_id')
                    ->references('id')->on('connectivity_node_containers')
                    ->onUpdate('cascade');
            });
        } catch (\Exception $e) {

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connectivity_nodes');
    }
}
