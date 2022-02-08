<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\User;
use App\AdminModels\AdminUserRole;
use App\AdminModels\AdminUserRolePivot;

// контроллер модели
class UserController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    // ------------------------------------------------------------------
    // вывод списка
    public function index()
    {
        // содержимое загрузить позже Vue

        // открыть вюшку
        return view('backend.user.index');
    }

    // ------------------------------------------------------------------
    // список (Vue)
    public function vueIndex(Request $request)
    {
        // переданные параметры через запрос post
        $getPage = $request['page']; // для пагинации
        $getFilterName = $request['filterName']; // фильтр - поисковое выражение в имени
        $getFilterUserRole = $request['filterUserRole']; // фильтр - роль Пользователя
        $getSortCol = $request['sortCol']; // сортировка
        $getSortDirect = $request['sortDirect']; // сортировка

        // для пагинации - кол-во записей на странице
        $rowsPerPage = $this->commonService->getAdmminSetting('setting_paginate_admin');

        $myReturn = User::with('role')
            ->selectRaw(
                'user.id, user.username, user.email, user.updated_at, 
                admin_user_role_pivots.user_role_id,
                admin_user_roles.name as role_name')
            ->leftJoin('admin_user_role_pivots', 'user.id', '=', 'admin_user_role_pivots.user_id')
            ->leftJoin('admin_user_roles', 'admin_user_role_pivots.user_role_id', '=', 'admin_user_roles.id')
            ->when(isset($getFilterName), function ($query) use ($getFilterName) {
                return $query->where('user.username', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getFilterUserRole) and $getFilterUserRole > 0, function ($query) use ($getFilterUserRole) {
                return $query->where('admin_user_role_pivots.user_role_id', $getFilterUserRole);
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

    // ------------------------------------------------------------------
    // удаление строки
    public function destroy($id)
    {
        // найти строчку
        $user = User::find($id);

        // удаление папки модели с ее содержимым
        if (isset($user->img)) {
            $this->commonFileService->serviceDeleteAllImages($user, 'img');
        }

        // удаление записи в БД
        // из pivot
        AdminUserRolePivot::where('user_id', $id)->delete();
        // с основной таблицы
        $user->delete();

        return true;
    }

    // ------------------------------------------------------------------
    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = User::findOrFail($id);
        } else {
            $content = new User;
        }

        // справочники и другие дополнительные сведения
        $roles = AdminUserRole::all()->where('name', '<>', 'vendor')->sortBy('sort');

        // открыть вьюшку
        return view('backend.user.edit', compact('content', 'roles'));
    }

    // ------------------------------------------------------------------
    // сохранение данных
    public function update($getUserID = null, Request $request)
    {
        // 1) сохранение Пользователя
        if ($getUserID == null) {
            $user = new User();
        } else {
            $user = User::find($getUserID);
        }

        $user->username = $request->username;
        $user->email = $request->email;

        // новый пароль
        $newPassword = $request->newPassword;
        if ($newPassword <> '') {
            $user->password = Hash::make($newPassword);
        }

        // сохраняем
        $user->save();

        // прочитать ID Пользователя, если он до этого не был указан
        if ($getUserID == null) {
            $getUserID = $user->id;
        }

        // 2) сохранение роли
        $userRoleID = $request->role_id;

        $userRolePivot = AdminUserRolePivot::where('user_id', $getUserID)->get()->first();
        if (!$userRolePivot) {
            $userRolePivot = new AdminUserRolePivot();
        }

        $userRolePivot->user_id = $getUserID;
        $userRolePivot->user_role_id = $userRoleID;

        // сохраняем
        $userRolePivot->save();

        // редирект
        return redirect(route('user.index'));
    }
}

