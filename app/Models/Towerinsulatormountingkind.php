<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Towerinsulatormountingkind extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerinsulatormountingkind";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Способ крепления изолятора на опоре";
    const title2 = "Способы крепления изоляторов на опорах";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/towerinsulatormountingkind/' . $this->id;
    }

    // связи
}
