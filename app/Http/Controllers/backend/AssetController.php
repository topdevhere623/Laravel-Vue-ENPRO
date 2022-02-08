<?php

namespace App\Http\Controllers\backend;

use App\DTO\AssetDTO;
use App\Http\Controllers\AppBaseController;
use App\Http\Services\backend\CommonService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// мои сервисы
use App\Http\Services\backend\CommonCrudService;

// модель
use App\Models\Asset;
use App\Models\Gost;
use App\Models\Manufacturer;

// контроллер модели

/**
 * @property CommonCrudService commonCrudService
 * @property CommonService commonService
 */
class AssetController extends AppBaseController
{
    /**
     * Подключение сервисов
     * AssetController constructor.
     * @param CommonCrudService $commonCrudService
     * @param CommonService $commonService
     */
    public function __construct(CommonCrudService $commonCrudService, CommonService $commonService)
    {
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
    }

    /**
     * Табличная часть объекта сс пагинацией
     * URL: api/asset
     * Method: GET
     * @return JsonResponse
     */
    public function index()
    {
        // пагинация
        $paginate = $this->commonService->getAdmminSetting('setting_paginate_admin');
        // получение данных модели
        $content = Asset::paginate($paginate);
        // отправляем JSON данные
        return response()->json($content);
    }

    /**
     * Вывод одной записи
     * URL: api/asset/{id}
     * Method: GET
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        //Показываем 1 запись с зависимыми записями
        $content = Asset::with('gost:id,name')
            ->with('manufacturer:id,name')
            ->find($id);
        // отправляем JSON данные
        return response()->json($content);
    }

    /**
     * Вызов данных при редактировании или создании объекта
     * URL: api/asset/{id}/edit
     * Method: GET
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id = 0)
    {
        // справочники и другие дополнительные сведения
        $gosts = Gost::all();
        $content = ((int) $id !== 0) ? Asset::find($id) : new \stdClass();
        $manufacturers = Manufacturer::all();
        // отправляем данные
        return response()->json(compact('content', 'gosts', 'manufacturers'));
    }

    /**
     * Cохранение данных при создании записи
     * URL: api/asset
     * Method: POST
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return $this->update($request);
    }

    /**
     * Cохранение данных при редактировании
     * URL: api/asset/{id}
     * Method: PUT || PATCH
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id = 0)
    {
        $validator = Validator::make($request->all(), [
            "gost_id" => "required",
            "manufacturer_id" => "required",
        ]);
        if(!$validator){
            return response()->json(["result" => false, "message" => $validator->getMessageBag()]);
        }
        try {
            if ((int)$id == 0) {
                Asset::query()->insert($request->all());
            } else {
                Asset::query()->where('id', $id)->update($request->all());
            }
        }catch (\Exception $e){
            return response()->json(["result" => false, "message" => $e->getMessage()]);
        }
        return response()->json(["result" => true]);
    }

    /**
     * удаление строки
     * URL: api/asset/{id}
     * Method: DELETE
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            Asset::findOrFail($id)->delete();
        }catch (\Exception $e){
            return response()->json(["result" => false, "message" => $e->getMessage()]);
        }
        return response()->json(["result" => true, "message" => ""]);
    }


    /**
     * Добавление Asset к модели
     * Method: GET
     * @param $modelName
     * @param $id
     * @return JsonResponse
     */
    public function getModelAsset(Request $request, $modelName, $id)
    {
        $modelFullName = 'App\\Models\\' . $modelName;
        $modelClass = new $modelFullName;
        if (! property_exists($modelClass, 'asset')) return response()->json(["result" => false, "message" => 'Have no method for save Asset']);

        /* @var Model $modelClass */
        $model = $modelFullName::with('asset')->findOrFail($id);
        $assetDTO = empty($model->asset()->first()) ? null : AssetDTO::instance()->load($model->asset()->first());
        $assetGroupIds = empty($model->asset()->first()) ? [] : $model->asset()->first()->getAssetGroupIds() ;
        return $this->sendResponse(['asset' => $assetDTO, 'asset_group_ids' => $assetGroupIds], 'Asset get successfully');
    }


    /**
     * Добавление Asset к модели
     * Method: POST
     * @param $modelName
     * @param $id
     * @return JsonResponse
     */
    public function setModelAsset(Request $request, $modelName, $id)
    {
        $modelFullName = 'App\\Models\\' . $modelName;
        $modelClass = new $modelFullName;
        if (! property_exists($modelClass, 'asset')) return response()->json(["result" => false, "message" => 'Have no method for save Asset']);

        /* @var Model $modelClass */
        $model = $modelFullName::findOrFail($id);
        $input = $request->all();
        $model->update(['asset' => $input]);
        return $this->sendResponse(AssetDTO::instance()->load($model->asset()->first()), 'Asset updated successfully');
    }

    /**
     * Список Asset для ТОиР
     * Method: get
     * @return JsonResponse
     */
    public function getEnproClassDefectAssets(Request $request, $enproClassDefectId)
    {
        $query = Asset::query()->where('enpro_class_defect_id', '=', $enproClassDefectId);
        $filterName = $request->get('search');
        if (! empty($filterName)) {
            $query->whereHas('IdentifiedObject', function($query) use ($filterName){
                $query->where('identifiedobject.name', 'like', '%' . $filterName . '%');
            });
        }

        $page = $request->get('page', 1);
        $size = $request->get('perPage', 10);
        $total = $query->count();
        $AssetRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return AssetDTO::instance()->loadToir($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($AssetRecords, 'Asset retrieved successfully', $meta);
    }

}
