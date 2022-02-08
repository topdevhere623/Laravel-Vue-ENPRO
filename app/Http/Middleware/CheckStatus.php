<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

// мои сервисы
use App\Http\Services\backend\CommonService;

class CheckStatus
{
    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    // кто имеет доступ к Админке
    public function handle($request, Closure $next)
    {
        // записать в журнал
        $this->commonService->newLog($request, 2);

        if (Auth::user() and (Auth::user()->isVendor() or Auth::user()->isAdmin() or Auth::user()->isManager() or Auth::user()->isDispatcher() or Auth::user()->isOperator() or Auth::user()->isMaster() or Auth::user()->isWorking())) {
            // доступ имеет
            return $next($request);
        } else {
            // доступ не имеет - переход на страницу login
            return redirect('login');
        }
    }
}