<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglePhaseKindsTable extends Migration
{
    public $tableName = 'single_phase_kinds';

    public function up()
    {
        if (Schema::hasTable($this->tableName)) return;
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('literal')->nullable();
            $table->integer('value')->nullable();
            $table->string('description')->nullable();
        });
        DB::statement("INSERT INTO single_phase_kinds (literal, description) VALUES
            ('A','Phase A.'),
            ('B','Phase B.'),
            ('C','Phase C.'),
            ('N','Neutral.'),
            ('s1','Secondary phase 1.'),
            ('s2','Secondary phase 2.'),
            ('s3','Secondary phase 3.')
        ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_phase_kinds');
    }
}
