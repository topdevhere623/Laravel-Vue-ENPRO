<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\EnproClassDefectResource;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;
use App\Models\EnproClassDefect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EnproClassDefectController extends Controller
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
        return view('backend.enproclassdefect.index');
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            return view('backend.enproclassdefect.edit', ["id" => $request->id]);
        }
        return view('backend.enproclassdefect.edit');
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
            return response()->json(["data" => EnproClassDefect::all()]);
        }

        $per_page = ($request->has('per_page')) ? (int) $request->per_page : $this->commonService->getAdmminSetting('setting_paginate_admin');
        $sortCol = ($request->has('sortCol')) ? $request->sortCol : 'id';
        $sortDirect = ($request->has('sortDirect')) ? $request->sortDirect : 'asc';
        if($request->has('search')){
            $res = EnproClassDefect::query()->orderBy($sortCol, $sortDirect)->search($request->search)->paginate($per_page);
        }else{
            $res = EnproClassDefect::query()->orderBy($sortCol, $sortDirect)->paginate($per_page);
        }
        return EnproClassDefectResource::collection($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => "required",
            "type" => "required",
            "class" => "required"
        ]);
        $res = EnproClassDefect::create($validated);

        return (new EnproClassDefectResource($res))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return EnproClassDefectResource
     */
    public function show($id)
    {
        return EnproClassDefect::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse|object
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "title" => "required",
            "type" => "required",
            "class" => "required"
        ]);
        $data = EnproClassDefect::findOrFail($id);
        $data->update($validated);

        return (new EnproClassDefectResource($data))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);
        try {
            EnproClassDefect::query()->whereIn('id', $ids)->delete();
        }catch (\Exception $e){
            return response(["message" => "Удаление запрещено. Имеются связанные записи!"], Response::HTTP_BAD_REQUEST);
        }
        return response([], Response::HTTP_NO_CONTENT);
    }
}
