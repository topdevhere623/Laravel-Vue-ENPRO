<?php

namespace App\Http\Services\backend;

use Doctrine\DBAL\Schema\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

// мои модели
use App\AdminModels\AdminSetting;
use App\AdminModels\AdminLog;

// общий сервис
class CommonService
{
    // вернуть текущее время с учетом текущего часового пояса
    public function getDateTime()
    {
        date_default_timezone_set("UTC"); // Устанавливаем часовой пояс по Гринвичу
        // текущее время
        $time = time();
        // смещение по часовому поясу
        $offset = (int)self::getAdmminSetting('setting_timezone');
        // добавляем это смещение
        $time += $offset * 3600;
        // возвращаемый параметр
        return date('Y-m-d H:i:s', $time);
    }

    // возвращает 1 минуту назад, час назад, день назад и т.д.
    public function getTimeDateAfter($getDateTime)
    {
        $retun = $getDateTime;

        // вернуть текущее время с учетом текущего часового пояса
        $currentDateTime = self::getDateTime();

        $retun = $currentDateTime - $getDateTime;

        // возвращаемый параметр
    }

    // получить заданную настройку Админки
    public function getAdmminSetting($key)
    {
        // возвращаемый параметр
        if(\Illuminate\Support\Facades\Schema::hasTable('admin_settings')) {
            return AdminSetting::where('key', $key)->get()->first()->value;
        } else return '';
    }

    // преобразование из 0/1 в Нет/Да
    public function changeDigitToYesNo($check)
    {
        return ($check == 1 ? 'Да' : 'Нет');
    }

    // записать в журнал новую запись
    public function newLog($request, $typeLog)
    {
        // для определения длительности
        $endTime = microtime(true);

        // новая строка
        $newLog = new AdminLog();
        // поля
        if ($typeLog == 1) {
            // это API
            $newLog->type = 'API';
            // параметры запроса (для web могут быть слишком длинные)
            $newLog->input = implode(',', $request->all()); // GetContent() // implode() // JSON.stringify()
        } else {
            // это web
            $newLog->type = 'web';
        }
        if(!defined('LARAVEL_START')) define('LARAVEL_START', time());
        $newLog->user_id = (auth()->check() ? Auth::user()->id : null);
        $newLog->time = self::getDateTime(); //gmdate('Y-m-d H:i:s');
        $newLog->duration = number_format($endTime - LARAVEL_START, 3);
        $newLog->ip = $request->ip(); // $_SERVER['REMOTE_ADDR'];
        $newLog->method = $request->method(); // $_SERVER['REQUEST_METHOD'];
        $newLog->url = $request->fullUrl(); // $_SERVER['REQUEST_URI'];
        $newLog->browser = @$_SERVER['HTTP_USER_AGENT'];

        //$newLog->status = $_SERVER['REDIRECT_STATUS']; // REDIRECT_STATUS // REDIRECT_REDIRECT_STATUS

        // сохранить
        $newLog->save();
    }

    // функция преобразования поля дататайм
    public function convertDateTimeTo_HHMM_DDMMYYYY($inputValue)
    {
        // если значение - это просто маска, то досрочный выход
        if ($inputValue == ':     -  -') return;

        // отделить время от даты
        $myDateTime = explode(" ", $inputValue);
        $myTime = $myDateTime[0];
        $myDate = $myDateTime[1];

        // разбор времени
        $myTime = explode(":", $myTime);
        // разбор даты
        $myDate = explode("-", $myDate);

        // перегруппированное значение
        $return = $myDate[2] . "-" . $myDate[1] . "-" . $myDate[0] . " " . $myTime[0] . ":" . $myTime[1];

        // возвращаемый параметр
        return $return;
    }
}
