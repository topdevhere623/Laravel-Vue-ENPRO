<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class EnproVehicleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enpro_vehicles')->delete();
        factory(\App\Models\EnproVehicle::class, 50)->create();
    }
}
