<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Towerconstructionwood extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerconstructionwood";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "Компонент - деревянный элемент";
    const title2 = "Компоненты - деревянные элементы";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/towerconstructionwood/' . $this->id;
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