<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Towerconstructionaggregate extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerconstructionaggregate";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "Сборный агрегат";
    const title2 = "Сборные агрегаты";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/towerconstructionaggregate/' . $this->id;
    }

    // связи
    // компоненты в сборных агрегатах (сводная) - как справочник (base, metal, wood...)
    public function towerconstructionaggregatepivots()
    {
        return $this->morphMany(Towerconstructionaggregatepivot::class, 'towerconstructionaggregatepivot');
    }

    // компоненты в сборных агрегатах (сводная) - как родитель
    public function towerconstructionaggregates()
    {
        return $this->hasMany(Towerconstructionaggregatepivot::class, 'towerconstructionaggregate_id');
    }
}
