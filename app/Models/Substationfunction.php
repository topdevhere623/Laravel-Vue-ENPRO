<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\IdentifiedObject;

// модель
class Substationfunction extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "substationfunction";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Функция подстанции";
    const title2 = "Функции подстанций";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/substationfunction/' . $this->id;
    }

    // связи
    // c идентификацией обьекта
    public function address()
    {
        return $this->belongsTo(IdentifiedObject::class, 'identifiedobject_id')->withDefault(['name' => 'Не определено']);
    }
}
