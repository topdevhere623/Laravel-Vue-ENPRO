<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\YandexMapService;

// модели

// контроллер яндекс-карты
class YandexMapController extends Controller
{
    // подключение сервисов
    public function __construct(YandexMapService $yandexMapService)
    {
        $this->yandexMapService = $yandexMapService;
    }

    // вывод списка
    public function index($modelName = 'identifiedobject')
    {
        // список точек для карты
        $return = $this->yandexMapService->getMapContent($modelName, false);
        $mapContent = $return['mapContent'];
        $mapTitle = $return['mapTitle'];
        $mapRoutNew = $return['mapRoutNew'];

        // открыть вьюшку
        return view('backend.yandex_map.index', compact('mapContent', 'mapTitle', 'mapRoutNew'));
    }
}


