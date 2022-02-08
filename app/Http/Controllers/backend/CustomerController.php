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
use App\Models\Customer;
use App\Models\Identifiedobject;
use App\Models\Acline;
use App\Models\Aclinesegment;
use App\Models\Span;

// контроллер модели
class CustomerController extends Controller
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
        // открыть вюшку
        return view('backend.customer.index');
    }

    // ------------------------------------------------------------------
    // список (Vue)
    public function vueIndex(Request $request)
    {
        // переданные параметры через запрос post
        $getPage = $request['page']; // для пагинации
        $getFilterName = $request['filterName']; // фильтр - поисковое выражение в имени
        $getSortCol = $request['sortCol']; // сортировка
        $getSortDirect = $request['sortDirect']; // сортировка

        // для пагинации - кол-во записей на странице
        $rowsPerPage = $this->commonService->getAdmminSetting('setting_paginate_admin');

        $myReturn = Customer::with('identifiedobject')
            ->selectRaw("customer.id, customer.updated_at, identifiedobject.id as io_id, identifiedobject.address, identifiedobject.lat, identifiedobject.long, 999999 as spanskol, 999999 as segmentskol, 999999 as aclineskol, SPACE(255) as aclinesname")
            ->leftJoin('identifiedobject', 'customer.identifiedobject_id', '=', 'identifiedobject.id')
            ->when(isset($getFilterName), function ($query) use ($getFilterName) {
                return $query->where('identifiedobject.address', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getSortCol), function ($query) use ($getSortCol, $getSortDirect) {
                return $query->orderBy($getSortCol, $getSortDirect);
            })
            ->where('customer.deleted_at', null)
            ->where('identifiedobject.deleted_at', null)
            ->paginate($rowsPerPage);

        // проставить кол-во
        if (isset($myReturn) and count($myReturn) > 0) {
            foreach ($myReturn as $key => $item) {

                $spansKol = 0;
                $segmentsKol = 0;
                $aclinesKol = 0;
                $aclinesName = '';

                // запрос к пролетам
                $spans = Span::with('aclinesegment')->where('startIO_id', $item['io_id'])->orWhere('endIO_id', $item['io_id'])->get();
                if (isset($spans) and count($spans) > 0) {
                    foreach ($spans as $span) {
                        // всего пролетов
                        $spansKol++;
                        // текущий пролет
                        $myCurrentSpan = $span['id'];

                        // запрос к сегменту
                        $segments = Aclinesegment::with('acline')->where('id', $span['aclinesegment_id'])->get();
                        if (isset($segments) and count($segments) > 0) {
                            foreach ($segments as $segment) {
                                // всего сегментов
                                $segmentsKol++;
                                // текущий сегмент
                                $myCurrentSegment = $segment['id'];

                                // запрос к линии
                                $aclines = Acline::with('identifiedobject')->where('id', $segment['acline_id'])->get();
                                if (isset($aclines) and count($aclines) > 0) {
                                    foreach ($aclines as $acline) {
                                        // всего сегментов
                                        $aclinesKol++;
                                        // имя текущей линии
                                        $myCurrentAclineName = $acline->identifiedobject->name;
                                        $aclinesName .= $myCurrentAclineName . ', ';
                                    }
                                }
                            }
                        }
                    }
                }

                $myReturn[$key]['spanskol'] = $spansKol;
                $myReturn[$key]['segmentskol'] = $segmentsKol;
                $myReturn[$key]['aclineskol'] = $aclinesKol;
                $myReturn[$key]['aclinesname'] = $aclinesName;

            }
        }

        // возвращаемый параметр
        return $myReturn;
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Customer', $id);
        // редирект
        return redirect()->back();
    }

    // вывод одной строки
    public function edit(Request $request, $id = null)
    {
        // проверить параметры, возможно переданные через post запрос
        $thisModal = $request->input('thisModal');

        // контент
        if ($id) {
            $content = Customer::find($id);
        } else {
            $content = new Customer;
        }

        // справочники и другие дополнительные сведения
        $identifiedobjects = Identifiedobject::all();

        // открыть вьюшку
        return view('backend.customer.edit', compact('content', 'identifiedobjects'));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        // проверить параметр модального окна
        $thisModal = $request->input('thisModal');

        $model = $this->commonCrudService->store('Customer', $id, $request);

        if (is_null($thisModal)) {
            // редирект
            return redirect(route('customer.index'));
        } else {
            // далее закрытие модального окна
            $id = $model->id;
            $model = Customer::find($id); // почемму то $model не может получить свое имя из IO
            $myName = $model->identifiedobject->name;
            return response()->json(['id' => $id, 'name' => $myName]);
        }
    }
}