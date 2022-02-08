<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

// мои сервисы
use App\Http\Services\backend\CommonService;

// записать в журнал (получается, что только для API это middleware работает. Для web занес в CheckStatus, а то конфликтовало при авторизации)
class SaveLog
{
    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    // записать в журнал
    public function handle($request, Closure $next, $typeLog)
    {
        // сделал как after middleware

        // продолжить выполнение
        $response = $next($request);

        // записать в журнал
        $this->commonService->newLog($request, $typeLog);

        return $response;
    }
}