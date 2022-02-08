<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class TodoStage extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "todo_stages";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Этап задачи";
    const title2 = "Этапы задач";

    // связи
    // с фио через сводную таблицу pivot. У одного этапа - несколько фио
    public function stages()
    {
        return $this->belongsToMany(Fio::class, 'todo_stage_fio_pivots', 'stage_id', 'fio_id');
    }
}

