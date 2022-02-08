<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\ModelService;

// модель
use App\AdminModels\AdminUserRole;

// контроллер ролей Пользователей
class AdminUserRoleController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->modelService = $modelService;
    }

    // вывод списка
    public function index()
    {
        // получение данных модели
        $content = AdminUserRole::all();

        // открыть вюшку
        return view('backend.admin_user_role.index', compact('content'));
    }
}