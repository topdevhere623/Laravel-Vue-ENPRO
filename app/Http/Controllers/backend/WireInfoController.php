<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\UnitMultiplier;
use App\Models\UnitSymbol;
use App\Models\WireInfo;
use App\Models\Length;
use App\Models\WireInsulationKind;
use App\Models\WireMaterialKind;
use App\Models\ResistancePerLength;
use App\Models\CurrentFlow;
use App\Models\AssetInfo;
use App\DTO\WireInfoDTO;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\Http\Requests\backend\CreateWireInfoRequest;

class WireInfoController extends AppBaseController
{

    /* @var CommonService $commonService */
    protected $commonService;

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = WireInfo::query();
        if (! empty($request->get('filterName'))) {
            $query->whereHas('AssetInfo', function($query) use ($request){
                $query->whereHas('IdentifiedObject', function($query) use ($request){
                    $query->where('identifiedobject.name', 'like', '%' . $request->get('filterName') .'%');
                });
            });
        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $WireInfoRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return WireInfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($WireInfoRecords, 'WireInfo retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = WireInfo::findOrFail($id);
        $dto = WireInfoDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'WireInfo retrieved successfully');
    }

    public function store(CreateWireInfoRequest $request)
    {
        $input = $request->all();

        $newArray = mergeArray($input);

        /** @var WireInfo $WireInfo */
        $model = WireInfo::create($newArray);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->setName($newArray['name']);
        $IdentifiedObject->save();

        return $this->sendResponse(WireInfoDTO::instance()->loadFull($model), 'WireInfo stored successfully');
    }

    public function update(CreateWireInfoRequest $request, $id)
    {
        /** @var WireInfo $model */
        $model = WireInfo::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->setName($input['name']);
        $IdentifiedObject->save();

        return $this->sendResponse(WireInfoDTO::instance()->loadFull($model), 'WireInfo updated successfully');
    }

    public function destroy($id)
    {
        /** @var WireInfo $model */
        $model = WireInfo::findOrFail($id);
        WireInfo::destroy($id);
        return $this->sendSuccess("WireInfo deleted successfully");
    }

    public function indexView()
    {
        return view('backend.wire_info.index');
    }

    public function editView($id = null)
    {
        if($id){
            return view('backend.wire_info.edit', ["id" => $id]);
        }
        return view('backend.wire_info.edit');
    }
}



