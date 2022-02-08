<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnproDefectRequest;
use App\Http\Resources\EnproDefectResource;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;
use App\Models\EnproClassDefect;
use App\Models\EnproDefect;
use App\Models\EnproGroupDefect;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EnproDefectController extends Controller
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
        return view('backend.enprodefect.index');
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            return view('backend.enprodefect.edit', ["id" => $request->id]);
        }
        return view('backend.enprodefect.edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if($request->enpro_class_defect_id && $request->enpro_group_defect_id) {
            $defects = EnproDefect::query()
                ->where('class_id', '=', $request->enpro_class_defect_id)
                ->where('group_id', '=', $request->enpro_group_defect_id);
            if($request->search) $defects->search($request->search);
            $defects = $defects->get();
            $res = [
                "data" => $defects,
                "meta"=> [
                    "pagination" => []
                ]
            ];
            return response()->json($res);
        }
        $per_page = ($request->has('per_page')) ? $request->per_page : $this->commonService->getAdmminSetting('setting_paginate_admin');
        $sortCol = ($request->has('sortCol')) ? $request->sortCol : 'id';
        $sortDirect = ($request->has('sortDirect')) ? $request->sortDirect : 'asc';

        $url = "&sortCol=$sortCol&sortDirect=$sortDirect&per_page=$per_page";
        $page = ($request->has('page')) ? $request->page : 1;
        $limit = (($page-1) * $per_page).", $per_page";


        $main_sql = "SELECT * FROM";
        $count_sql = "SELECT count(*) as  cnt FROM";

        $sql = "(
            SELECT
              enpro_defect.id,
              enpro_defect.title,
              enpro_defect.code,
              enpro_defect.critical,
              enpro_group_defect.id AS group_id,
              enpro_group_defect.code_group AS group_code,
              enpro_group_defect.title AS group_title,
              enpro_class_defect.id AS class_id,
              enpro_class_defect.type AS class_type,
              enpro_class_defect.class AS class_class,
              enpro_class_defect.title AS class_title
            FROM enpro_defect
              INNER JOIN enpro_class_defect
                ON enpro_defect.class_id = enpro_class_defect.id
              INNER JOIN enpro_group_defect
                ON enpro_defect.group_id = enpro_group_defect.id
            ) s ";

        if($request->has('search')){
            $search = $request->search;
            $url .= "&search=$search";
            $sql .= " where upper(concat(title, code, group_code, group_title, class_type, class_class, class_title)) like upper('%$search%')";
        }

        if($request->has('filter')){
            $req = $request->all();
            unset($req['page']);
            unset($req['sortCol']);
            unset($req['sortDirect']);
            unset($req['filter']);
            unset($req['search']);
            unset($req['per_page']);
            if(count($req) > 0) {
                $sql .= (!strpos($sql, 'where')) ? " where " : " and ";
                $i = 0;
                $url .= "&filter";
                foreach ($req as $k => $v) {
                    $url .= "&$k=$v";
                    if ($i > 0) $sql .= ' and ';
                    $sql .= " upper($k) like upper('%$v%')";
                    $i++;
                }
            }
        }

        $q = DB::selectOne($count_sql.$sql);
        $total = $q->cnt;
        $lastPage = round($total / $per_page);
        if($lastPage < 0) $lastPage = 0;

        $sql .= " order by $sortCol $sortDirect LIMIT $limit";
        $data = DB::select($main_sql.$sql);

        $pagination = [
            "first_page_url" => asset('api/enpro_defect?page=1'.$url),
            "last_page_url"  => ($lastPage <= 0) ? "null" : asset('api/enpro_defect?page='.$lastPage.$url),
            "prev_page_url"  => ($page <= 1) ? null :  asset('api/enpro_defect?page='.($page-1).$url),
            "next_page_url"  => ($page >= $lastPage) ? null : asset('api/enpro_defect?page='.($page+1).$url),
            "current_page" => (int) $page,
            "from" =>  ($page*$per_page)+1,
            "last_page" => $lastPage,
            "path" => asset('api/enpro_defect'),
            "per_page" => (int)$per_page,
            "to" => ($per_page * 2),
            "total" => $total
        ];
        $res = [
            "data" => $data,
            "meta"=> [
                "pagination" => $pagination,
            ]
        ];

        return response()->json($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "code" => "required",
            "title" => "required",
            "critical" => "required",
            "class_id" => "required",
            "group_id" => "required"
        ]);

        $res = EnproDefect::create($validate);
        return (new EnproDefectResource($res))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return EnproDefectResource
     */
    public function show(int $id)
    {
        $res = EnproDefect::with('enproClassDefect')
            ->with('enproGroupDefect')
            ->find($id);
        return [
            "data" => $res,
            "enproGroup" => EnproGroupDefect::all(),
            "enproClass" => EnproClassDefect::all()
        ];
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
            "code" => "required",
            "title" => "required",
            "critical" => "required",
            "class_id" => "required",
            "group_id" => "required"
        ]);

        $data = EnproDefect::findOrFail($id);
        $data->update($validate);

        return (new EnproDefectResource($data))
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
            EnproDefect::query()->whereIn('id', $ids)->delete();
        }catch(\Exception $e){
            return response(["message" => "Удаление запрещено. Имеются связанные записи!"], Response::HTTP_BAD_REQUEST);
        }
        return response([], Response::HTTP_NO_CONTENT);
    }
}


