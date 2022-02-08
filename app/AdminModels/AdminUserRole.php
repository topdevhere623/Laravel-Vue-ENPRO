<?php

namespace App\AdminModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// ts новая модель
class AdminUserRole extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "admin_user_roles";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Роль Пользователя";
    const title2 = "Роли Пользователей";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/userrole/' . $this->id;
    }

    // связи
}
