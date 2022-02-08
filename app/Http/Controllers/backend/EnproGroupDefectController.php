<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnproGroupDefectResource;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;
use App\Models\EnproDefect;
use App\Models\EnproGroupDefect;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EnproGroupDefectController extends Controller
{

    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    public function indexView()
    {
        return view('backend.enprogroupdefect.index');
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            return view('backend.enprogroupdefect.edit', ["id" => $request->id]);
        }
        return view('backend.enprogroupdefect.edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if($request->has('all')){
            return response()->json(["data" => EnproGroupDefect::all()]);
        }

        $per_page = ($request->has('per_page')) ? (int) $request->per_page : $this->commonService->getAdmminSetting('setting_paginate_admin');
        $sortCol = ($request->has('sortCol')) ? $request->sortCol : 'id';
        $sortDirect = ($request->has('sortDirect')) ? $request->sortDirect : 'asc';
        if($request->enpro_class_defect_id) {
            $res = EnproGroupDefect::query()->select('enpro_group_defect.*')->leftJoin(
                'enpro_defect',
                'enpro_group_defect.id',
                'group_id'
            )->where('enpro_defect.class_id', '=', $request->enpro_class_defect_id);
            if($request->has('search')) $res->search();
            $res = $res->groupBy('enpro_group_defect.id')->get();
            return EnproGroupDefectResource::collection($res);
        }
        if($request->has('search')){
            $res = EnproGroupDefect::query()->orderBy($sortCol, $sortDirect)->search($request->search)->paginate($per_page);
        }else{
            $res = EnproGroupDefect::query()->orderBy($sortCol, $sortDirect)->paginate($per_page);
        }

        return EnproGroupDefectResource::collection($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|object
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "code_group" => "required",
            "title" => "required"
        ]);
        $res = EnproGroupDefect::create($validate);
        return (new EnproGroupDefectResource($res))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return EnproGroupDefectResource
     */
    public function show(int $id)
    {
        return EnproGroupDefect::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        $validate = $request->validate([
            "code_group" => "required",
            "title" => "required"
        ]);
        $data = EnproGroupDefect::findOrFail($id);
        $data->update($validate);

        return (new EnproGroupDefectResource($data))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return Response
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);
        try {
            EnproGroupDefect::query()->whereIn('id', $ids)->delete();
        }catch (\Exception $e){
            return response(["message" => "Удаление запрещено. Имеются связанные записи!"], Response::HTTP_BAD_REQUEST);
        }
        return response([], Response::HTTP_NO_CONTENT);
    }
}
