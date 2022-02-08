<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель (ts сам создал)
class Towerconstructionbase extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerconstructionbase";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Компонент - фундамент";
    const title2 = "Компоненты - фундаменты";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/towerconstructionbase/' . $this->id;
    }

    // связи
    // компоненты (сводная)
    public function towerconstructionpivots()
    {
        return $this->morphMany(Towerconstructionpivot::class, 'towerconstructionpivot');
    }

    // компоненты в сборных агрегатах (сводная)
    public function towerconstructionaggregatepivots()
    {
        return $this->morphMany(Towerconstructionaggregatepivot::class, 'towerconstructionaggregatepivot');
    }

    // компоненты в реальных опорах (сводная)
    public function towerconstructionrealpivots()
    {
        return $this->morphMany(Towerconstructionrealpivot::class, 'towerconstructionrealpivot');
    }
}