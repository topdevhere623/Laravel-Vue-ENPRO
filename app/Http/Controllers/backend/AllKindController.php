<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\DTO\AllKindDTO;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AllKindController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request, $modelName)
    {
        $modelFullName = 'App\\Models\\' . $modelName;
        $query = $modelFullName::query();

        $search = $request->get('search');
        if (! empty($search)) {
            $query->where(function($query) use ($search) {
                $query->where('value', 'like', '%' . $search . '%')
                    ->orWhere('ru_value', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $page = $request->get('page', 1);
        $size = $request->get('perPage', 10);
        if ($size > 0) {
            $total = $query->count();
            $records = $query->offset(($page-1)*$size)
                ->limit($size)
                ->get()
                ->map(function($model) {
                    return AllKindDTO::instance()->load($model);
                });
            $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
            $meta = ['pagination' => $pagination];
        } else {
            $records = $query->get()
                ->map(function($model) {
                    return AllKindDTO::instance()->load($model);
                });
            $meta = [];
        }
        return $this->sendResponse($records, "$modelName retrieved successfully", $meta);
    }

    public function show($modelName, $id)
    {
        $modelFullName = 'App\\Models\\' . $modelName;
        $model = $modelFullName::findOrFail($id);
        $dto = AllKindDTO::instance()->load($model);
        return $this->sendResponse($dto, "$modelName retrieved successfully");
    }

    public function store(Request $request, $modelName)
    {
        $modelFullName = 'App\\Models\\' . $modelName;
        $input = $request->all();
        $model = $modelFullName::create($input);

        return $this->sendResponse(AllKindDTO::instance()->load($model), "$modelName stored successfully");
    }

    public function update(Request $request, $modelName, $id)
    {
        $modelFullName = 'App\\Models\\' . $modelName;
        $model = $modelFullName::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(AllKindDTO::instance()->load($model), "$modelName updated successfully");
    }

    public function destroy($modelName, $id)
    {
        $modelFullName = 'App\\Models\\' . $modelName;
        $model = $modelFullName::findOrFail($id);
        $model->delete($id);
        return $this->sendSuccess("$modelName deleted successfully");
    }

    public function massDestroy($modelName)
    {
        $modelFullName = 'App\\Models\\' . $modelName;
        $modelFullName::whereIn('id', request()->get('ids'))->delete();
        return $this->sendSuccess("$modelName deleted successfully");
    }


    public function indexView()
    {
        return view('backend.all_kind.index', ['kinds' => config('kinds')]);
    }

    public function editView(Request $request, $modelName, $id = null, $enum = null)
    {   
        if($enum == 1) {
            if($id){
                return view('backend.all_enum_kind.edit', ["model" => $modelName, "id" => $id]);
            }
            return view('backend.all_enum_kind.edit', ["model" => $modelName]);
        } else if ($enum == 0) {
            if($id){
                return view('backend.all_kind.edit', ["model" => $modelName, "id" => $id]);
            }
            return view('backend.all_kind.edit', ["model" => $modelName]);
        }
    }

}
