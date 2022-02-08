<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitMultiplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unit_multiplier = array(
            array('id' => '1','value' => '-24','literal' => 'y','description' => 'Yocto 10**-24.'),
            array('id' => '2','value' => '-21','literal' => 'z','description' => 'Zepto 10**-21.'),
            array('id' => '3','value' => '-18','literal' => 'a','description' => 'Atto 10**-18.'),
            array('id' => '4','value' => '-15','literal' => 'f','description' => 'Femto 10**-15.'),
            array('id' => '5','value' => '-12','literal' => 'p','description' => 'Pico 10**-12.'),
            array('id' => '6','value' => '-9','literal' => 'n','description' => 'Nano 10**-9.'),
            array('id' => '7','value' => '-6','literal' => 'micro','description' => 'Micro 10**-6'),
            array('id' => '8','value' => '-3','literal' => 'm','description' => 'Milli 10**-3'),
            array('id' => '9','value' => '-2','literal' => 'c','description' => 'Centi 10**-2'),
            array('id' => '10','value' => '-1','literal' => 'd','description' => 'Deci 10**-1.'),
            array('id' => '11','value' => '0','literal' => 'none','description' => 'No multiplier or equivalently multiply by 1. '),
            array('id' => '12','value' => '1','literal' => 'da','description' => 'Deca 10**1.'),
            array('id' => '13','value' => '2','literal' => 'h','description' => 'Hecto 10**2'),
            array('id' => '14','value' => '3','literal' => 'k','description' => 'Kilo 10**3.'),
            array('id' => '15','value' => '6','literal' => 'M','description' => 'Mega 10**6.'),
            array('id' => '16','value' => '9','literal' => 'G','description' => 'Giga 10**9.'),
            array('id' => '17','value' => '12','literal' => 'T','description' => 'Tera 10**12. '),
            array('id' => '18','value' => '15','literal' => 'P','description' => 'Peta 10**15. '),
            array('id' => '19','value' => '18','literal' => 'E','description' => 'Exa 10**18.  '),
            array('id' => '20','value' => '21','literal' => 'Z','description' => 'Zetta 10**21.'),
            array('id' => '21','value' => '24','literal' => 'Y','description' => 'Yotta 10**24.')
        );
        DB::table("unit_multiplier")->insert($unit_multiplier);

    }
}
