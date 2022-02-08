<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Disconnectorfuseinfo extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "disconnectorfuseinfo";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Информация о разъединителе-предохранителе";
    const title2 = "Информация о разъединителях-предохранителях";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/disconnectorfuseinfo/' . $this->id;
    }

    // связи
}
