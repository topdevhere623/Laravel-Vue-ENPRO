<?php

use Illuminate\Database\Seeder;

class EnproToolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enpro_tools')->delete();
        factory(\App\Models\EnproTool::class, 50)->create();    }
}
