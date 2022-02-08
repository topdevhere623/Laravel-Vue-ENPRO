<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

// мои сервисы
use App\Http\Services\backend\ModelService;

// модели
use App\Models\Material;

class MaterialTableSeeder extends Seeder
{
    // подключение сервисов
    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function run()
    {
        // из-за того, что по разному называются таблицы materialkind (Fb) и material (MySQL) - здесь импорт вручную
        // получить данные из таблицы Firebird
        /* remove FireBird Connection from seeds */
    }
}
