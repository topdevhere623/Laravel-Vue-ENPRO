<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;

// модели
use App\AdminModels\AdminLog;

// контроллер log-действия Пользователя
class AdminLogController extends Controller
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
        // содержимое загрузить позже Vue

        // открыть вьюшку
        return view('backend.admin_log.index');
    }

    // ------------------------------------------------------------------
    // список (Vue)
    public function vueIndex(Request $request)
    {
        // переданные параметры через запрос post
        $getPage = $request['page']; // для пагинации
        $getFilterName = $request['filterName']; // фильтр - поисковое выражение в имени
        $getFilterUserName = $request['filterUserName']; // фильтр - имя Пользователя
        $getFilterAdminLogType = $request['filterAdminLogType']; // фильтр - тип журнала
        $getSortCol = $request['sortCol']; // сортировка
        $getSortDirect = $request['sortDirect']; // сортировка

        // для пагинации - кол-во записей на странице
        $rowsPerPage = $this->commonService->getAdmminSetting('setting_paginate_admin');

        $myReturn = AdminLog::with('user')
            ->selectRaw(
                'admin_log.id, admin_log.type as adminlogtype, admin_log.time, admin_log.duration, admin_log.ip, admin_log.method, admin_log.url, admin_log.input, admin_log.browser, 
                user.username')
            ->leftJoin('user', 'admin_log.user_id', '=', 'user.id')
            ->when(isset($getFilterName), function ($query) use ($getFilterName) {
                return $query
                    ->where('user.username', 'like', '%' . $getFilterName . '%')
                    ->Orwhere('admin_log.url', 'like', '%' . $getFilterName . '%')
                    ->Orwhere('admin_log.input', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getFilterUserName) and $getFilterUserName > 0, function ($query) use ($getFilterUserName) {
                return $query->where('admin_log.user_id', $getFilterUserName);
            })
            ->when(isset($getFilterAdminLogType) and $getFilterAdminLogType <> "0", function ($query) use ($getFilterAdminLogType) {
                return $query->where('admin_log.type', $getFilterAdminLogType);
            })
            ->when(isset($getSortCol), function ($query) use ($getSortCol, $getSortDirect) {
                return $query->orderBy($getSortCol, $getSortDirect);
            })
            ->paginate($rowsPerPage);

        // возвращаемый параметр
        return $myReturn;
    }

    // ------------------------------------------------------------------
    // удаление (Vue)
    public function vueDelete(Request $request)
    {
        // переданные параметры через запрос post
        $selectedRows = $request['selectedRows'];
        // преобразовать строчку в массив
        $selectedRows = array_map('intval', explode(',', $selectedRows)); // выделенные строчки

        // сканировать полученный список
        if ($selectedRows and count($selectedRows) > 0) {
            foreach ($selectedRows as $item) {
                $delete = self::destroy($item);
            }
        }
    }

    // удаление строки
    public function destroy($id)
    {
        // найти строчку
        $adminlog = AdminLog::find($id);

        // удаление из базы
        $adminlog->delete();

        return true;
    }
}


