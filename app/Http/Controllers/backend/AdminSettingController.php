<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;

// модели
use App\AdminModels\AdminSetting;

// контроллер настроек Админки
class AdminSettingController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
    }

    // вывод списка
    public function index()
    {
        // пагинация
        $paginate = $this->commonService->getAdmminSetting('setting_paginate_admin');
        // контент
        $content = AdminSetting::orderByRaw("CASE WHEN 'sort'=0 THEN 999999 ELSE 'sort' END asc")->paginate($paginate);
        // открыть вьюшку
        return view('backend.admin_setting.index', compact('content'));
    }

    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = AdminSetting::findOrFail($id);
        } else {
            $content = new AdminSetting;
        }
        // открыть вьюшку
        return view('backend.admin_setting.edit', compact('content'));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        $bool = $this->commonCrudService->store('AdminSetting', $id, $request);
        // если не прошло валидацию
        if ($bool == false) return redirect()->back();
        // редирект
        return redirect(route('admin_setting.index'));
    }
}


