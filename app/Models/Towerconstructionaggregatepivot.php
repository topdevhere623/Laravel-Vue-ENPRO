<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Towerconstructionaggregatepivot extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerconstructionaggregate_pivots";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "Сборный агрегат (сводная)";
    const title2 = "Сборные агрегаты (сводная)";

    // связи
    // со справочниками компонентов
    public function towerconstructionpivot()
    {
        return $this->morphTo();
    }

    // со сборными агрегатами
    public function towerconstructionaggregate()
    {
        return $this->belongsTo(Towerconstructionaggregate::class, 'towerconstructionaggregate_id')->withDefault();
    }
}
