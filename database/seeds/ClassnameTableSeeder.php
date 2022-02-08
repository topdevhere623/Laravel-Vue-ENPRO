<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

// мои сервисы

// модели
use App\Models\Classname;

class ClassnameTableSeeder extends Seeder
{
    // подключение сервисов
    public function __construct()
    {
    }

    public function run()
    {
        // из-за того, что таблицы по разному называются - здесь импорт вручную
        // получить данные из таблицы Firebird
        //$content = $this->firebirdService->zaprosData('Classnamemarktype', null);
        /* remove FireBird Connection from seeds */


        // и еще добавить одну строчку с connetor
        $id = DB::table('classname')->insertGetId([
            'name' => "Фидер",
            'keylink' => strtoupper(Str::random(32)),
            'classname' => "Connector",
            'marktypekey' => strtoupper(Str::random(32)),
        ]);

        // и еще добавить одну строчку с connetor
        $id = DB::table('classname')->insertGetId([
            'name' => "Конечная точка",
            'keylink' => strtoupper(Str::random(32)),
            'classname' => "EndPoint",
            'marktypekey' => strtoupper(Str::random(32)),
        ]);

        // и еще добавить одну строчку с tower
        $id = DB::table('classname')->insertGetId([
            'name' => "Опора",
            'keylink' => strtoupper(Str::random(32)),
            'classname' => "Tower",
            'marktypekey' => strtoupper(Str::random(32)),
        ]);

        // и еще добавить одну строчку с acline
        $id = DB::table('classname')->insertGetId([
            'name' => "ЛЭП",
            'keylink' => strtoupper(Str::random(32)),
            'classname' => "Acline",
            'marktypekey' => strtoupper(Str::random(32)),
        ]);

        // и еще добавить одну строчку с acline
        $id = DB::table('classname')->insertGetId([
            'name' => "Сегмент",
            'keylink' => strtoupper(Str::random(32)),
            'classname' => "Aclinesegment",
            'marktypekey' => strtoupper(Str::random(32)),
        ]);

        // и еще добавить одну строчку с span
        $id = DB::table('classname')->insertGetId([
            'name' => "Пролет",
            'keylink' => strtoupper(Str::random(32)),
            'classname' => "Span",
            'marktypekey' => strtoupper(Str::random(32)),
        ]);

        // и еще добавить одну строчку с customer
        $id = DB::table('classname')->insertGetId([
            'name' => "Потребитель",
            'keylink' => strtoupper(Str::random(32)),
            'classname' => "Customer",
            'marktypekey' => strtoupper(Str::random(32)),
        ]);
    }
}
