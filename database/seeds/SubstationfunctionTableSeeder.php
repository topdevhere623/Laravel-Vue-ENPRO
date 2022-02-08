<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

// мои сервисы
use App\Http\Services\backend\ModelService;

// модели
use App\Models\Substationfunction;

class SubstationfunctionTableSeeder extends Seeder
{
    // подключение сервисов
    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function run()
    {
        // из-за того, что kind в новой таблице отсутсвует и поля по разному назвали - здесь импорт вручную
        // получить данные из таблицы Firebird
        //$content = $this->firebirdService->zaprosData('Substationfunctionkind', null);
        /* remove FireBird Connection from seeds */
        $content = [];
        // сканировать эти данные
        foreach ($content as $item) {
            // модель MySQL
            $model = new Substationfunction();
            // прочитать строку
            $model->function = $item->SUBSTATIONFUNCTION;
            $model->description = $item->SUBSTATIONFUNCTIONDESCRIPTION;
            // сохранить
            $model->save();
        }
    }
}
