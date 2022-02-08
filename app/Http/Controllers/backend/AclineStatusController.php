<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\AclineStatus;

// контроллер модели
class AclineStatusController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    // ------------------------------------------------------------------
    // вывод списка
    public function index()
    {
        // содержимое загрузить позже Vue

        // открыть вюшку
        return view('backend.aclinestatus.index');
    }

    // ------------------------------------------------------------------
    // список (Vue)
    public function vueIndex(Request $request)
    {
        // переданные параметры через запрос post
        $getPage = $request['page']; // для пагинации
        $getFilterName = $request['filterName']; // поисковое выражение
        $getSortCol = $request['sortCol']; // сортировка
        $getSortDirect = $request['sortDirect']; // сортировка

        // для пагинации - кол-во записей на странице
        $rowsPerPage = $this->commonService->getAdmminSetting('setting_paginate_admin');

        $myReturn = AclineStatus
            ::when(isset($getFilterName), function ($query) use ($getFilterName) {
                return $query->where('name', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getSortCol), function ($query) use ($getSortCol, $getSortDirect) {
                return $query->orderBy($getSortCol, $getSortDirect);
            })
            ->paginate($rowsPerPage);

        // возвращаемый параметр
        return $myReturn;
    }

    // ------------------------------------------------------------------
    // сохранение строки справочника (Vue)
    public function vueSave(Request $request)
    {
        // переданные параметры через запрос post
        $getSpravRowContent = json_decode($request['spravRowContent'], true); // содержимое строки в справочнике

        // сохранение строки
        if (isset($getSpravRowContent['id']) and $getSpravRowContent['id'] > 0) {
            $modelSave = AclineStatus::find($getSpravRowContent['id']);
        } else {
            $modelSave = new AclineStatus;
        }

        // проверка, есть ли данные в полученном массиве
        $myFields = ['name'];
        foreach ($myFields as $item) {
            if (isset($getSpravRowContent[$item])) {
                $modelSave->$item = $getSpravRowContent[$item];
            }
        }

        // сохранить
        $modelSave->save();
        // присвоенный ID, если его еще не было
        $newID = $modelSave->id;
        // заново прочитать всю строку (с новым id, статусом, датой обновления и пр.)
        $modelSave = AclineStatus::find($newID);

        // возвращаемый параметр
        return $modelSave;
    }

    // ------------------------------------------------------------------
    // групповая обработка строк (Vue)
    public function vueSelectedRows(Request $request)
    {
        // переданные параметры через запрос post
        $getSelectedRows = $request['selectedRows'];
        $getRegim = $request['regim'];
        // преобразовать строчку в массив
        $getSelectedRows = array_map('intval', explode(',', $getSelectedRows)); // выделенные строчки

        // сканировать полученный список
        if ($getSelectedRows and count($getSelectedRows) > 0) {
            foreach ($getSelectedRows as $item) {
                switch ($getRegim) {
                    case 'delete':
                        // групповое удаление
                        self::destroy($item);
                        break;
                }
            }
        }
    }

    // ------------------------------------------------------------------
    // удаление строки
    public function destroy($getID)
    {
        // удаление изображений и в бд
        $this->commonCrudService->destroy('AclineStatus', $getID);
    }
}
