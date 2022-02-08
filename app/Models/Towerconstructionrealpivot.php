<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Towerconstructionrealpivot extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerconstructionreal_pivots";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "Компонент в реальной опоре (сводная)";
    const title2 = "Компоненты в реальной опоре (сводная)";

    // связи
    // со справочниками компонентов
    public function towerconstructionpivot()
    {
        return $this->morphTo();
    }

    // с опорами
    public function tower()
    {
        return $this->belongsTo(Tower::class, 'tower_id')->withDefault();
    }

    // с марками опор
    public function towerinfo()
    {
        return $this->belongsTo(Towerinfo::class, 'towerinfo_id')->withDefault();
    }

    // со сборными агрегатами
    public function towerconstructionaggregate()
    {
        return $this->belongsTo(Towerconstructionaggregate::class, 'towerconstructionaggregate_id')->withDefault();
    }
}
