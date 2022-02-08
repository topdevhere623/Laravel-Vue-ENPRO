<?php

namespace App\AdminModels;

use Illuminate\Database\Eloquent\Model;

// ts новая модель настройки
class AdminSetting extends Model
{
    // управляемая таблица
    protected $table = "admin_settings";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];

    // мои атрибуты модели
    const title1 = "Настройка";
    const title2 = "Список настроек";
}
