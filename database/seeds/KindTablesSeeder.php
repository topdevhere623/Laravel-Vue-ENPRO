<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class KindTablesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'ru_value' => 'Тип конструкции 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Тип конструкции 2',
            ],
        ];
        try {
            DB::table('transformer_construction_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Тип сердечника 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Тип сердечника 2',
            ],
        ];
        try {
            DB::table('transformer_core_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Функциональное назначение 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Функциональное назначение 2',
            ],
        ];
        try {
            DB::table('transformer_function_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Вид охлаждения 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Вид охлаждения 2',
            ],
        ];
        try {
            DB::table('transformer_cooling_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Вид конструкции кабеля 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Вид конструкции кабеля 2',
            ],
        ];
        try {
            DB::table('cable_construction_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Исполнение по пожароопасности 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Исполнение по пожароопасности 2',
            ],
        ];
        try {
            DB::table('enpro_fire_safety_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Тип брони 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Тип брони 2',
            ],
        ];
        try {
            DB::table('cable_shield_material_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Материал наружной оболочки 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Материал наружной оболочки 2',
            ],
        ];
        try {
            DB::table('cable_outer_jacket_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Принцип гашения дуги 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Принцип гашения дуги 2',
            ],
        ];
        try {
            DB::table('breaker_construction_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Вид размещения ДГУ 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Вид размещения ДГУ 2',
            ],
        ];
        try {
            DB::table('interrupter_position_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Номинальная частота питания вкл. и отк. устройств, вспом. цепей и цепей упр. 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Номинальная частота питания вкл. и отк. устройств, вспом. цепей и цепей упр. 2',
            ],
        ];
        try {
            DB::table('secondary_circuits_voltage_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'ru_value' => 'Климатическое исполнение и категория размещения 1',
            ],
            [
                'id' => 2,
                'ru_value' => 'Климатическое исполнение и категория размещения 2',
            ],
        ];
        try {
            DB::table('gost_climatic_mod_placement_kind')->insert($data);
        } catch (Exception $e) {
        }
        $data = [
            [
                'id' => 1,
                'value' => 'медные жилы',
                'ru_value' => 'медные жилы',
            ],
            [
                'id' => 2,
                'value' => 'алюминиевые жилы',
                'ru_value' => 'алюминиевые жилы',
            ],
        ];
        try {
            DB::table('wire_material_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'value' => 'ПВХ пластикат',
                'ru_value' => 'ПВХ пластикат',
            ],
            [
                'id' => 2,
                'value' => 'сшитый полиэтилен',
                'ru_value' => 'сшитый полиэтилен',
            ],
        ];
        try {
            DB::table('wire_insulation_kind')->insert($data);
        } catch (Exception $e) {
        }

        $data = [
            [
                'id' => 1,
                'value' => 'winding insulation kind 1',
                'ru_value' => 'Тип изоляции 1',
            ],
            [
                'id' => 2,
                'value' => 'winding insulation kind 2',
                'ru_value' => 'Тип изоляции 2',
            ],
        ];
        try {
            DB::table('winding_insulation_kind')->insert($data);
        } catch (Exception $e) {
        }
    }
}
