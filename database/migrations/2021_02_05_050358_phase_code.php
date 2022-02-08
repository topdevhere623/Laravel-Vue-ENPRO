<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhaseCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phasecode', function (Blueprint $table) {
            //
            $table->id();
            $table->integer('value')->default(0);
            $table->char('literal', 5)->nullable();
            $table->string('description')->default('');
        });
        DB::statement("INSERT INTO phasecode (literal,`value`,description) VALUES
('ABCN',225,'Phases A, B, C, and N.'),
('ABC',224,'Phases A, B, and C.'),
('ABN',193,'Phases A, B, and neutral.'),
('ACN',41,'Phases A, C and neutral.'),
('BCN',97,'Phases B, C, and neutral.'),
('AB',132,'Phases A and B.'),
('AC',96,'Phases A and C.'),
('BC',66,'Phases B and C.'),
('AN',129,'Phases A and neutral.'),
('BN',65,'Phases B and neutral.'),
('CN',33,'Phases C and neutral.'),
('A',128,'Phase A.'),
('B',64,'Phase B.'),
('C',32,'Phase C.'),
('N',16,'Neutral phase.'),
('s1N',528,'Secondary phase 1 and neutral.'),
('s2N',272,'Secondary phase 2 and neutral.'),
('s12N',784,'Secondary phases 1, 2, and neutral.'),
('s1',512,'Secondary phase 1.'),
('s2',256,'Secondary phase 2.'),
('s12',768,'Secondary phase 1 and 2.'),
('none',0,'No phases specified.'),
('X',1024,'Unknown non-neutral phase.'),
('XY',3072,'Two unknown non-neutral phases.'),
('XN',1040,'Unknown non-neutral phase plus neutral.'),
('XYN',3088,'Two unknown non-neutral phases plus neutral');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phasecode', function (Blueprint $table) {
            //
        });
    }
}
