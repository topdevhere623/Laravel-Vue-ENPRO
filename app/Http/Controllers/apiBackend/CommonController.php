<?php

namespace App\Http\Controllers\apiBackend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\ModelService;
use App\Http\Services\backend\YandexMapService;

// модель
use App\Models\Aclinesegmentinfo;

// контроллер api бекенда по общим действиям
class CommonController extends Controller
{
    // подключить сервисы
    public function __construct(ModelService $modelService, CommonFileService $commonFileService, YandexMapService $yandexMapService)
    {
        $this->commonFileService = $commonFileService;
        $this->modelService = $modelService;
        $this->yandexMapService = $yandexMapService;
    }

    // выполнение сырого запроса
    public function runAnyQuery(Request $request)
    {
        // переданные параметры через запрос post
        $getQuery = $request['getQuery'];

        // выполнние сырого запроса
        $myReturn = DB::select($getQuery);

        // возвращаемый параметр
        return $myReturn;
    }

    // получить все записи указанной модели
    public function getModelRecords(Request $request)
    {
        // переданные параметры через запрос post
        $getModelName = ucfirst(trim($request['modelName'])); // сделать первую большую букву
        $getModelID = $request['modelID'];

        // создание экземпляра модели по ее имени
        $model = $this->modelService->makeModel($getModelName);

        // получение всех ее строк и связанных данных
        switch (true) {
            case (in_array($getModelName, ["Acline", "Segments", "Span", "Tower", "Customer", "Disconnector", "Discharger", "Crossing", "Substation", "Terminal", "Busbarsection", "Endpoint", "Connector"])):

                if ($getModelID != null) {
                    $return = $model::with('identifiedobject')->where('id', $getModelID)->get()->first();
                } else {
                    $return = $model::with('identifiedobject')->get();
                }

                break;
            case ($getModelName == "TodoStageFioPivot"):

                $return = $model::with('stage', 'fio')->where('todo_id', $getModelID)->get();

                break;
            default:

                if ($getModelID != null) {
                    $return = $model::where('id', $getModelID)->get()->first();
                } else {
                    $return = $model::all();
                }
        }

        // возвращаемый параметр
        return $return;
    }

    // получить список ближайших обьектов по дистанции
    public function getNearObjectsOnDistance(Request $request)
    {
        // переданные параметры через запрос post
        $getModelName = $request['modelName']; // поисковое значение
        $getCurrentCoords = $request['currentCoords']; // имя модели

        // получить список ближайших обьектов по дистанции
        $return = $this->yandexMapService->getNearObjectsOnDistance($getModelName, $getCurrentCoords);

        // возвращаемый параметр
        return $return;
    }

    // получить список ближайших обьектов в видимой области карты
    // уже не используется в коде!!!!!!!!!
    public function getNearObjects(Request $request)
    {
        // переданные параметры через запрос post
        $getCurrentBounds = $request['currentBounds']; // координаты видимой части карты

        // получить список ближайших обьектов по дистанции
        $getArrOnBounds = $this->yandexMapService->getNearObjects('tower', $getCurrentBounds);

        // то, что делал на фронте
        $myWidthMax = 1280; // 1920
        $myHeightMax = 1024; // 1080
        $myScaleSVG = 1;

        $myColorActive = 'red';
        $myColorFill = '#ccc';

        $myFigure = '';

        $myMinLat = $getCurrentBounds[0][0];
        $myMaxLat = $getCurrentBounds[1][0];
        $myMinLong = $getCurrentBounds[0][1];
        $myMaxLong = $getCurrentBounds[1][1];

        // отразить по горизонтали (из-за того, что отсчет по Y в SVG идет вниз)
        $temp = $myMaxLat;
        $myMaxLat = $myMinLat;
        $myMinLat = $temp;

        // для расчета координата
        $pxOneX = ($myMaxLong - $myMinLong) / ($myWidthMax);
        $pxOneY = ($myMaxLat - $myMinLat) / ($myHeightMax);
        if ($pxOneX === 0) $pxOneX = 1;
        if ($pxOneY === 0) $pxOneY = 1;

        // текущий масштаб карты
        $myCurrentScaleMap = 15;
        // радиус точки
        $myPointR = 3;
        if ($myCurrentScaleMap > 14 && $myCurrentScaleMap <= 16) $myPointR = 5;
        if ($myCurrentScaleMap > 16 && $myCurrentScaleMap <= 18) $myPointR = 8;
        if ($myCurrentScaleMap > 18 && $myCurrentScaleMap <= 20) $myPointR = 15;
        if ($myCurrentScaleMap > 20) $myPointR = 40;
        $myPointR = 15;

        // крайние точки
        if (count($getArrOnBounds) > 0) {
            // да, точки есть

            // нанести на холст SVG
            foreach ($getArrOnBounds as $item) {
                $myX = ($item['long'] - $myMinLong) / $pxOneX;
                $myY = ($item['lat'] - $myMinLat) / $pxOneY;
                $myFigure .= '<circle r="' . $myPointR . '" cx="' . $myX . '" cy="' . $myY . '" fill="' . $myColorActive . '" onclick="myFun()"/>';
            }
        }

        // холст SVG
        $mySVGMap = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ' .
            'viewBox = "0 0 ' . $myWidthMax . ' ' . $myHeightMax . '" ' .
            'width = "' . ($myWidthMax * $myScaleSVG) . '" ' .
            'height = "' . ($myHeightMax * $myScaleSVG) . '">' .
            //'<script xlink:href="public/uploads/svgMapScripts.js"/>' .
            '<script><![CDATA[' .
            'function myFun() {' .
            'alert(222);' .
            '}' .
            ']]></script>' .
            '<g onclick="myFun()">' . $myFigure . '</g>' .
            '</svg>';

        // возвращаемый параметр
        return $mySVGMap;

        // возвращаемый параметр
        //return $return;
    }

    // закачать файлы к папку модели
    public function uploadModelFiles(Request $request)
    {
        // переданные параметры через запрос post
        $getModelDir = $request['getModelDir'];
        $getModelID = $request['getModelId'];

        // рабочая директория
        $dir = 'uploads/models/' . $getModelDir . '/' . $getModelID; // $content->folderPath()
        // сохранение
        $path = $request->file('image')->store($dir, 'public');

        // возвращаемый параметр
        return $path;
    }

    // получить список файлов из папки модели
    public function getModelFiles(Request $request)
    {
        // переданные параметры через запрос post
        $getModelDir = $request['getModelDir'];

        // рабочая директория
        $dir = 'uploads/models/' . $getModelDir;

        // получить список файлов в текущей директории
        $allFiles = Storage::disk('public')->Files($dir);
        $content = [];
        foreach ($allFiles as $file) {
            // название без пути
            $name = str_replace($dir . '/', '', $file);
            // размер файла
            $size = File::size($file);
            // тип файла
            $mime = pathinfo($file, PATHINFO_EXTENSION); //$this->commonFileService->getMimeType($file);
            // можно ли будет показать в img
            $thisimg = ($mime == 'jpg' or $mime == 'jpeg' or $mime == 'png' or $mime == 'gif') ? true : false;
            // записать в итоговый массив
            $content [] = ['name' => $name, 'size' => $size, 'mime' => $mime, 'thisimg' => $thisimg];
        }

        // возвращаемый параметр
        return $content;
    }

    // удалить файл в папке модели
    public function deleteModelFile(Request $request)
    {
        // переданные параметры через запрос post
        $getFile = 'uploads/models/' . $request['getFile'];

        // удалить файл с диска
        Storage::disk('public')->delete($getFile);
    }
}
