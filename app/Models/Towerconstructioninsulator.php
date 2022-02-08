<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Towerconstructioninsulator extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerconstructioninsulator";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "Компонент - изолятор";
    const title2 = "Компоненты - изоляторы";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/towerconstructioninsulator/' . $this->id;
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