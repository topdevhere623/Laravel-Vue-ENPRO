<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Loadbreakswitch extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "loadbreakswitch";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Выключатель нагрузки";
    const title2 = "Выключатели нагрузки";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/loadbreakswitch/' . $this->id;
    }

    // связи
}
