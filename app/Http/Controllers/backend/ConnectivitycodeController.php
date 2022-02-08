<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\Busbarsection;
use App\Models\Connectivitycode;
use App\Models\Substation;
use App\Models\Terminal;

// контроллер api бекенда по общим действиям
class ConnectivitycodeController extends Controller
{
    // подключить сервисы
    public function __construct(ModelService $modelService, CommonFileService $commonFileService)
    {
        $this->commonFileService = $commonFileService;
        $this->modelService = $modelService;
    }

    // получить список секций шин по ТП
    public function getBusbarsectionsOnSubstation(Request $request)
    {
        // переданные параметры через запрос post
        $getFindValue = $request['findValue']; // поисковое значение
        $getBaseVoltage = $request['baseVoltage']; // класс напряжения

        // запрос
        $return = Busbarsection::with('identifiedobject')
            ->where('substation_id', $getFindValue)
            ->get();

        // возвращаемый параметр
        return $return;
    }

    // получить список терминалов по секции шин
    public function getTerminalsOnBusbarsection(Request $request)
    {
        // переданные параметры через запрос post
        $getFindValue = $request['findValue']; // поисковое значение
        $getBaseVoltage = $request['baseVoltage']; // класс напряжения

        // запрос
        $return = Terminal::with('identifiedobject')
            ->where('busbarsection_id', $getFindValue)
            ->get();

        // возвращаемый параметр
        return $return;
    }

    // получить терминал по его коду подключения
    public function getTerminalOnConnectivitycode(Request $request)
    {
        // переданные параметры через запрос post
        $getFindValue = $request['findValue']; // поисковое значение

        // запрос
        $return = Terminal::with('identifiedobject')->where('connectivitycode_id', $getFindValue)->get()->first();

        // возвращаемый параметр
        return $return;
    }
}
