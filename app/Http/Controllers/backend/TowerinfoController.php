<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\Towerinfo;
use App\Models\Towermaterial;
use App\Models\Towerkind;
use App\Models\Towerconstructionpivot;

// контроллер модели
class TowerinfoController extends Controller
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
        return view('backend.towerinfo.index');
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

        $myReturn = Towerinfo::with('towerconstructions')
            ->selectRaw('towerinfo.id, towerinfo.name, towerinfo.mark, towerinfo.series, towerinfo.album, towerinfo.img, towerinfo.strut, towerinfo.status, towerinfo.updated_at, towermaterial.name as towermaterial_name, towerkind.name as towerkind_name')
            ->leftJoin('towermaterial', 'towerinfo.towermaterial_id', '=', 'towermaterial.id')
            ->leftJoin('towerkind', 'towerinfo.towerkind_id', '=', 'towerkind.id')
            ->when(isset($getFilterName), function ($query) use ($getFilterName) {
                return $query
                    ->where('towerinfo.name', 'like', '%' . $getFilterName . '%')
                    ->Orwhere('towerinfo.mark', 'like', '%' . $getFilterName . '%')
                    ->Orwhere('towerinfo.series', 'like', '%' . $getFilterName . '%')
                    ->Orwhere('towerinfo.album', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getSortCol), function ($query) use ($getSortCol, $getSortDirect) {
                return $query->orderBy($getSortCol, $getSortDirect);
            })
            ->paginate($rowsPerPage);

        // возвращаемый параметр
        return $myReturn;
    }

    // ------------------------------------------------------------------
    // групповая обработка строк (Vue)
    public function vueDelete(Request $request)
    {
        // переданные параметры через запрос post
        $selectedRows = $request['selectedRows'];
        $getRegim = $request['regim'];
        $getFile = $request['file'];
        // преобразовать строчку в массив
        $selectedRows = array_map('intval', explode(',', $selectedRows)); // выделенные строчки

        // сканировать полученный список
        if ($selectedRows and count($selectedRows) > 0) {
            foreach ($selectedRows as $item) {

                switch ($getRegim) {
                    case 'delete':
                        // групповое удаление
                        self::vueDestroy($item);
                        break;
                    case 'copy':
                        // групповое создание копий
                        // прочитать исходник
                        $modelLoad = Towerinfo::with('towerconstructions')->where('id', $item)->get()->first();

                        // сохранить основные данные
                        $modelSave = new Towerinfo;
                        $modelSave->name = $modelLoad['name'] . '_copy';
                        $modelSave->mark = $modelLoad['mark'];
                        $modelSave->series = $modelLoad['series'];
                        $modelSave->album = $modelLoad['album'];
                        $modelSave->weight = $modelLoad['weight'];
                        $modelSave->wiren = $modelLoad['wiren'];
                        $modelSave->towermaterial_id = $modelLoad['towermaterial_id'];
                        $modelSave->towerkind_id = $modelLoad['towerkind_id'];
                        $modelSave->strut = $modelLoad['strut'];
                        $modelSave->status = $modelLoad['status'];
                        $modelSave->save();
                        // присвоенный ID
                        $newID = $modelSave->id;

                        // сохранить pivot
                        if (isset($modelLoad->towerconstructions) and count($modelLoad->towerconstructions) > 0) {
                            foreach ($modelLoad->towerconstructions as $item) {
                                $modelPivotSave = new Towerconstructionpivot();
                                $modelPivotSave->towerinfo_id = $newID;
                                $modelPivotSave->towerconstructionpivot_type = $item['towerconstructionpivot_type'];
                                $modelPivotSave->towerconstructionpivot_id = $item['towerconstructionpivot_id'];
                                $modelPivotSave->kol = $item['kol'];
                                $modelPivotSave->save();
                            }
                        }
                        break;
                }
            }
        }
    }

    // ------------------------------------------------------------------
    // удаление строки (Vue)
    public function vueDestroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Towerinfo', $id);
        // удаление из pivot
        Towerconstructionpivot::where('towerinfo_id', $id)->delete();
    }

    // ------------------------------------------------------------------
    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = Towerinfo::find($id);
        } else {
            $content = new Towerinfo;
        }

        // справочники и другие дополнительные сведения

        // открыть вьюшку
        return view('backend.towerinfo.edit', compact('content'));
    }

    // ------------------------------------------------------------------
    // сохранение данных (Vue) - вкладка основное
    public function vueSave(Request $request)
    {
        // переданные параметры через запрос post
        $getModelID = $request['modelID'];
        $getModelData = json_decode($request['modelData'], true);

        if (isset($getModelID) and $getModelID > 0) {
            $modelSave = Towerinfo::find($getModelID);
        } else {
            $modelSave = new Towerinfo;
        }

        // подготовка полей для сохранение в основной таблице
        // проверка, есть ли данные в полученном массиве
        $myFields = ['name', 'mark', 'series', 'album', 'wiren', 'towermaterial_id', 'towerkind_id', 'strut', 'status', 'img', 'img_draft'];
        foreach ($myFields as $item) {
            if (isset($getModelData[$item])) {
                $modelSave->$item = $getModelData[$item];
            }
        }

        // сохранить
        $modelSave->save();
        // присвоенный ID, если его еще не было
        $newID = $modelSave->id;
        // заново прочитать всю строку (с новым id, статусом, датой обновления и пр.)
        $modelSave = Towerinfo::find($newID);

        // возвращаемый параметр
        return $modelSave;
    }
}
