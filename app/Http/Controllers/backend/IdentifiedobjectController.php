<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// моя валидация
use App\Rules\myLat;
use App\Rules\myLong;

// модель
use App\Models\Identifiedobject;
use App\Models\Classname;
use App\Models\Subclass;
use App\Models\BaseVoltage;
use App\Models\Asset;

//use App\Models\Enobj; // нет такой таблицы!!!
use App\Models\Subcontrolarea;
use App\Models\Bay;
use App\Models\Role;
use App\Models\Connector;

// контроллер модели
class IdentifiedobjectController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    // вывод списка
    public function index()
    {
        // // пагинация
        // $paginate = $this->commonService->getAdmminSetting('setting_paginate_admin');
        // // получение данных модели
        // $content = Identifiedobject::paginate($paginate);

        // открыть вюшку
        return view('backend.identifiedobject.index');
    }

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
        // $rowsPerPage= 2;ЫЫ

        $myReturn = Identifiedobject::with('classname', 'basevoltage')
            ->when(isset($getFilterName), function ($query) use ($getFilterName) {
                return $query->where('name', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getSortCol), function ($query) use ($getSortCol, $getSortDirect) {
                return $query->orderBy($getSortCol, $getSortDirect);
            })
            ->paginate($rowsPerPage);

        // возвращаемый параметр
        return $myReturn;
    }

    // удаление (Vue)
    public function vueDelete(Request $request)
    {
        // переданные параметры через запрос post
        $selectedRows = $request['selectedRows'];
        // преобразовать строчку в массив
        $selectedRows = array_map('intval', explode(',', $selectedRows)); // выделенные строчки

        // сканировать полученный список
        if ($selectedRows and count($selectedRows) > 0) {
            foreach ($selectedRows as $item) {
                $delete = self::destroy($item);
            }
        }
    }

    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = Identifiedobject::with('acline', 'basevoltage', 'classname')->findOrFail($id);
        } else {
            $content = new Identifiedobject;
        }

        // справочники и другие дополнительные сведения
        $classnames = Classname::all();
        $subclasses = Subclass::all();
        $basevoltages = BaseVoltage::all(); // нет такого Voltage, взял из BaseVoltage
        $assets = Asset::all();
        $enobj = ''; // Enobj::all(); // нет такого!!!
        $subcontrolareas = Subcontrolarea::all();
        $bays = Bay::all(); // !!! в Bay нет данные, есть в Bayinfo
        $roles = Role::all();
        $connectors = Connector::all();

        // открыть вьюшку
        return view('backend.identifiedobject.edit', compact('content', 'classnames', 'subclasses', 'basevoltages', 'assets', 'enobj', 'subcontrolareas', 'bays', 'roles', 'connectors'));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {

        // валидация
        $this->validate($request, [
            'name' => 'required|min:1',
            'lat' => ['numeric', new myLat],
            'long' => ['numeric', new myLong],
        ]);

        $bool = $this->commonCrudService->store('Identifiedobject', $id, $request);
        // редирект
        return redirect(route('identifiedobject.index'));
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Identifiedobject', $id);
        // редирект
        return redirect()->back();
    }
}
