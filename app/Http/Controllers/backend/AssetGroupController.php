<?php

namespace App\Http\Controllers\backend;

use App\DTO\AssetDTO;
use App\DTO\AssetGroupDTO;
use App\DTO\AssetGroupKindDTO;
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
use App\Models\AssetGroup;
use App\Models\Document;
use App\Models\AssetGroupKind;

// контроллер модели

/**
 * @property CommonCrudService commonCrudService
 * @property CommonService commonService
 */
class AssetGroupController extends AppBaseController
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

    public function index()
    {
        $assetGroups = AssetGroup::with('Document')
            ->with('Document.IdentifiedObject')
            ->get()
            ->map(function($q) {
                return AssetGroupDTO::instance()->load($q);
            });
        return response()->json($assetGroups);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /* @var AssetGroup $assetGroup */

        $assetGroup = AssetGroup::create($input);
        $assetGroup->setName($input['name']);
        $assetGroup->getIdentifiedObject()->save();

        return response()->json(AssetGroupDTO::instance()->load($assetGroup));
    }

    public function getAssetGroupKindList()
    {
        $assetGroups = AssetGroupKind::get()
            ->map(function($q) {
                return AssetGroupKindDTO::instance()->load($q);
            });
        return response()->json($assetGroups);
    }
}
