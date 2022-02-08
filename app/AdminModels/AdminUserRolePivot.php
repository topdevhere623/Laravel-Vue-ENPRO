<?php

namespace App\AdminModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\User;
use App\AdminModels\AdminUserRole;

// ts новая модель для связи пользователей с ролями
class AdminUserRolePivot extends Model
{
    // подключение трайтов
    use CommonTrait;

    // управляемая таблица
    protected $table = "admin_user_role_pivots";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Роль Пользователя (сводная)";
    const title2 = "Роли Пользователей (сводная)";

    // связи
}
