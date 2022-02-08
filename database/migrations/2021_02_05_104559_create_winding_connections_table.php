<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWindingConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winding_connections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->char('literal')->default('');
            $table->string('description')->default('');
            $table->integer('value')->default(0);
        });

        DB::statement("INSERT INTO winding_connections (literal,description) VALUES
        ('D', 'Delta.'),
        ('Y', 'Wye.'),
        ('Z', 'ZigZag.'),
        ('Yn', 'Wye, with neutral brought out for grounding.'),
        ('Zn', 'ZigZag, with neutral brought out for grounding.'),
        ('A', 'Autotransformer common winding.'),
        ('I', 'Independent winding, for single-phase connections.');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winding_connections');
    }
}
