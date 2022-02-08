<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class TodoStageFioPivot extends Model
{
    // подключение трайтов
    use CommonTrait;

    // управляемая таблица
    protected $table = "todo_stage_fio_pivots";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "ФИО этапа задачи (сводная)";
    const title2 = "ФИО этапов задач (сводная)";

    // связи
    // с этапами
    public function stage()
    {
        return $this->belongsTo(TodoStage::class, 'stage_id')->withDefault(['name' => 'Не определено']);
    }

    // с фио
    public function fio()
    {
        return $this->belongsTo(Fio::class, 'fio_id')->withDefault(['name' => 'Не определено']);
    }
}

