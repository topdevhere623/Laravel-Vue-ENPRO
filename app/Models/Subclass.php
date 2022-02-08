<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Subclass extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "subclass";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Подкласс";
    const title2 = "Подклассы";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/subclass/' . $this->id;
    }

    // связи
    // с родительским классом
    public function classname()
    {
        return $this->belongsTo(Classname::class, 'classname_id')->withDefault(['classname' => 'Не определено']);
    }
}
