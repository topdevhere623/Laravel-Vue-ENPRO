<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\DTO\GostDTO;
use App\Models\Gost;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class GostController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = Gost::query();

        $search = $request->get('search');
        if (! empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $page = $request->get('page', 1);
        $size = $request->get('perPage', 10);
        if ($size > 0) {
            $total = $query->count();
            $records = $query->offset(($page-1)*$size)
                ->limit($size)
                ->get()
                ->map(function($model) {
                    return GostDTO::instance()->load($model);
                });
                $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
                $meta = ['pagination' => $pagination];
        } else {
            $records = $query->get()
                ->map(function($model) {
                    return GostDTO::instance()->load($model);
                });
            $meta = [];
        }
        return $this->sendResponse($records, "Gost retrieved successfully", $meta);
    }

    public function show($modelName, $id)
    {
        $model = Gost::findOrFail($id);
        $dto = GostDTO::instance()->load($model);
        return $this->sendResponse($dto, "$modelName retrieved successfully");
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $model = Gost::create($input);

        return $this->sendResponse(GostDTO::instance()->load($model), "Gost stored successfully");
    }

    public function update(Request $request, $id)
    {
        $model = Gost::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(GostDTO::instance()->load($model), "Gost updated successfully");
    }

    public function destroy($id)
    {
        $model = Gost::findOrFail($id);
        $model->delete($id);
        return $this->sendSuccess("Gost deleted successfully");
    }

    public function massDestroy()
    {
        Gost::whereIn('id', request()->get('ids'))->delete();
        return $this->sendSuccess("Gost deleted successfully");
    }


    public function indexView()
    {
        return view('backend.all_kind.index');
    }

    public function editView(Request $request, $id = null)
    {
        if($id){
            return view('backend.gost.edit', ["id" => $id]);
        }
        return view('backend.gost.edit');
    }

}
