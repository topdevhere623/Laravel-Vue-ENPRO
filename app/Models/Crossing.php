<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;
use App\Traits\ImageTrait;

// модели
use App\Models\Identifiedobject;
use App\Models\Span;
use App\Models\Crossingtype;

// модель (ts сам создал)
class Crossing extends Model
{
    // подключение трайтов
    use CommonTrait;
    use ImageTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "crossing";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Пересечение местности";
    const title2 = "Пересечения местности";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/crossing/' . $this->id;
    }

    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с пролетом
    public function span()
    {
        return $this->belongsTo(Span::class, 'span_id')->withDefault();
    }

    // с типами пересечения местности
    public function crossingtype()
    {
        return $this->belongsTo(Crossingtype::class, 'crossingtype_id')->withDefault();
    }
}

