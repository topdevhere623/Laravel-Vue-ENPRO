<?php

namespace App\Http\Services\backend;

use App\Models\Identifiedobject;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// мои сервисы
use App\Http\Services\backend\CommonFileService;

// сервис CRUD для моделей (сохранение, удаление...)
class CommonCrudService
{
    // подключение общего сервиса работы с файлами (изображениями)
    public function __construct(CommonFileService $commonFileService)
    {
        $this->commonFileService = $commonFileService;
    }

    // сохранение
    public function store($modelName, $id = null, $request, $fieldImg = null, $filesSaveRegim = null)
    {
        //        DB::beginTransaction();
        //        try {
        // класс
        $myClass = "\App\\" . (Str::contains($modelName, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($modelName);
        if ($id) {
            if (is_numeric($id)) {
                // id
                $model = $myClass::findOrFail($id);
            } else {
                // slug
                $model = $myClass::where('slug', $id)->firstOrFail();
            }
        } else {
            $model = new $myClass;
        }

        // получение финального запроса (в полях изменить с on на 1 и получение массива с полями, которые нужно исключить -токен и все, что касается изображений)
        $requestExcept = self::getExceptRequest($request);
        // сохраняем все кроме изображения (при новом еще id не знаем)
        //dd($requestExcept);
        if ($id) {
            $model->fill($requestExcept)->update();
        } else {
            $model->fill($requestExcept)->save();
        }

        // сохранение данных в IO, если есть
        self::store_io($request, $model);

        switch ($filesSaveRegim) {
            case 'file':
                self::store_file_as_model($request, $model, $fieldImg);
                return $model;
                break;

            default:
                # code...
                break;
        }

        // сохранение изображения
        if (is_null($filesSaveRegim)) {
            // сохранение изображения - в своей же модели
            self::store_img_as_model($request, $model, $fieldImg);
        } else {
            // сохранение изображения в дочерней связующей таблице pivot
            self::store_img_as_pivot($request, $model, $modelName);
        }

        // попытка сделать транзакцию
        //            DB::commit();
        //        } catch (\Exception $exception) {
        //            // транзакция не прошла - отменить все действия
        //            DB::rollback();
        //            throw new $exception($exception->getMessage());
        //        }

        // возвращаемый параметр
        return $model;
    }

    // получение финального запроса (в полях изменить с on на 1 и получение массива с полями, которые нужно исключить -токен и все, что касается изображений)
    public function getExceptRequest($request)
    {
        // список всех полей
        $requestAll = $request->all();
        // массив с исключенными полями
        $requestMinus = ['_token', 'thisModal'];
        foreach ($requestAll as $key => $item) {
            if ($key == 'status') $request->merge(['status' => self::serviceChangeOnToOne(@$request['status'])]);
            if ($key == 'guy') $request->merge(['guy' => self::serviceChangeOnToOne(@$request['guy'])]);
            if ($key == 'strut') $request->merge(['strut' => self::serviceChangeOnToOne(@$request['strut'])]);
            if ($key == 'annex') $request->merge(['annex' => self::serviceChangeOnToOne(@$request['annex'])]);
            if (Str::contains($key, 'sort')) $request->merge([$key => self::serviceChangeSortToZero(@$request[$key])]);
            if (Str::contains($key, 'img')) $requestMinus[] = $key;
        }

        // исключить поля из запроса
        $return = $request->except($requestMinus);

        // возвращаемый параметр
        return $return;
    }

    // сохранение изображения - в своей же модели
    public function store_file_as_model($request, $model, $fieldFile)
    {
        //dd($fieldFile);

        // удаление старого изображения с диска
        //$this->commonFileService->serviceDeleteOldFile($model, $fieldFile);
        // обновляем изображения (id в любом случае уже известно)
        $model->$fieldFile = $this->commonFileService->serviceUploadedFile($request['scheme'], $model);
        $model->update();
    }

    // сохранение изображения - в своей же модели
    public function store_img_as_model($request, $model, $fieldImg)
    {
        // проверка, изменяли ли изображение (заменили на другое или очистили)
        if ($model->$fieldImg <> $request['img-current']) {
            // удаление старого изображения с диска
            $this->commonFileService->serviceDeleteOldImage($model, $fieldImg);
            // обновляем изображения (id в любом случае уже известно)
            $model->$fieldImg = $this->commonFileService->serviceUploadedImage($request['img'], $model);
            $model->update();
        }
    }

    // сохранение изображения в дочерней связующей таблице pivot
    public function store_img_as_pivot($request, $model, $modelName)
    {
        // имя модели pivot
        $myClassPivot = "\App\\" . (Str::contains($modelName, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($modelName) . 'FilePivot';
        // имя поля в pivot с именем, как имя модели
        $myFieldInPivot = strtolower($modelName) . '_id';
        // удаление всех записи в БД таблицы pivot для этой id модели
        $contentFilPivots = $myClassPivot::where($myFieldInPivot, $model->id)->delete();

        // список всех полей
        $requestAll = $request->all();
        foreach ($requestAll as $key => $item) {
            if (Str::contains($key, 'pivot_img_')) {
                $contentFilPivots = new $myClassPivot();
                $contentFilPivots->$myFieldInPivot = $model->id;
                $contentFilPivots->file_id = $item;
                $contentFilPivots->save();
            }
        }
    }

    // сохранение данных в IO, если есть
    public function store_io($request, $model)
    {
        // прогнать все поля - отобрать с _io_
        $requestAll = $request->all();
        $requestIO = [];
        foreach ($requestAll as $key => $item) {
            if (Str::contains($key, '_io_')) {
                $fieldIO = str_replace('_io_', '', $key);
                $requestIO[] = [$fieldIO, $item];
            }
        }
        // сохранить эти поля в IO
        if (count($requestIO) > 0) {
            if (is_null($model->identifiedobject->id)) {
                // id родителя нет - режим новый
                $io = new Identifiedobject;
            } else {
                // дочерняя строчка существует
                $io = Identifiedobject::find($model->identifiedobject->id);
            }
            foreach ($requestIO as $item) {
                $field = $item[0];
                $value = $item[1];
                if (!is_null($item[1])) $io->$field = $value;
            }
            $io->save();

            // обновить в родителе
            $model->identifiedobject_id = $io->id;
            $model->save();
        }
    }

    // удаление
    public function destroy($model, $id, $fieldImg = 'img'): bool
    {
        // класс
        $myClass = "\App\\" . (Str::contains($model, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($model);
        $model = $myClass::find($id);
        // удаление папки модели с ее содержимым
        if (isset($model->$fieldImg)) {
            $this->commonFileService->serviceDeleteAllImages($model, $fieldImg);
        }
        // удаление записи в БД
        $model->delete();
        return true;
    }

    // вспомогательное ----------------------------------------------------------------

    // преобразование чекбокса с on на 1
    public function serviceChangeOnToOne($check)
    {
        $return = isset($check) ? 1 : 0;
        return $return;
    }

    // записать в сортировку 0, если null (потому что в бд убрал null по умолчанию, т.к. мешало сортировке)
    public function serviceChangeSortToZero($sort)
    {
        if (is_null($sort) or $sort == '') {
            return 0;
        } else {
            return $sort;
        }
    }
}
