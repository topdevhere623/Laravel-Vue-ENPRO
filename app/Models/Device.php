<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель (ts сам создал)
class Device extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "device";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Устройство (планшет)";
    const title2 = "Устройства (планшеты)";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/device/' . $this->id;
    }

    // связи
    // с пользователями user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
