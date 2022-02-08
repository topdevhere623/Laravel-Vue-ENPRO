<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Towerconstructionpivot extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerconstruction_pivots";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "Компонент (сводная)";
    const title2 = "Компоненты (сводная)";

    // связи
    // со справочниками компонент
    public function towerconstructionpivot()
    {
        return $this->morphTo();
    }

    // с марками опор
    public function towerinfo()
    {
        return $this->belongsTo(Towerinfo::class, 'towerinfo_id')->withDefault();
    }
}
