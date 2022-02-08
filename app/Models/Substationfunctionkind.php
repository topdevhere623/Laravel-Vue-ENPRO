<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\IdentifiedObject;

// модель (ts сам создал)
class Substationfunctionkind extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "substationfunctionkind";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Вид функции подстанции";
    const title2 = "Виды функций подстанций";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/substationfunctionkind/' . $this->id;
    }

    // связи
    // c идентификацией обьекта
    public function address()
    {
        return $this->belongsTo(IdentifiedObject::class, 'identifiedobject_id')->withDefault(['name' => 'Не определено']);
    }
}
