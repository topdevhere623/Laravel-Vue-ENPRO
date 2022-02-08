<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Identifiedobject;
use App\Models\Substation;

// модель
class Busbarsectioninfo extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "busbarsectioninfo";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Информация по секции шины";
    const title2 = "Информация по секциям шин";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/busbarsectioninfo/' . $this->id;
    }

    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с подстанцией
    public function substation()
    {
        return $this->belongsTo(Substation::class, 'substation_id')->withDefault();
    }
}
