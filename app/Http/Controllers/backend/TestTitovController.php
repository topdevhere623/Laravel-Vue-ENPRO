<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Substation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\Acline;
use App\Models\Identifiedobject;
use App\Models\Tower;

// контроллер для тестов
class TestTitovController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    // тестовый контроллер Сергей Титов
    public function index()
    {
        $tower = Tower::where('id', 3)->get()->first();

        //$tower3->setName(1, 'имя в 1-ой линии');
        //$tower3->setName(2, 'имя во 2-ой линии');

        //$tower3NewName1 = $tower3->getName(1);
        //$tower3NewName2 = $tower3->getName(2);

        dd(
            $tower->getName(),
            $tower->getName(1),
            $tower->getName(2)); // $tower, $tower->nameObject,
    }
}
