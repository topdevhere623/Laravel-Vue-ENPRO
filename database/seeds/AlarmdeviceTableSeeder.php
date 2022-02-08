<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

// мои сервисы
use App\Http\Services\backend\ModelService;

// модели
use App\Models\Alarmdevice;

class AlarmdeviceTableSeeder extends Seeder
{
    // подключение сервисов
    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function run()
    {
        /* remove FireBird Connection from seeds */
    }
}
