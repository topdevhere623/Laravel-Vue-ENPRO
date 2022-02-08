<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;
use App\Traits\ImageTrait;

// модели
use App\Models\TodoStageFioPivot;
use App\Models\Company;
use App\Models\File;

// модель
class Todo extends Model
{
    // подключение трайтов
    use CommonTrait;
    use ImageTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "todo";

    // список полей, разрешенных на редактирование
    protected $fillable = ['name', 'description', 'date_begin', 'date_end', 'files', 'status_id'];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];
    // преоразование типов полей
    protected $casts = [
        'files' => 'array',
    ];

    // мои атрибуты модели
    const title1 = "Задача";
    const title2 = "Задачи";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/todo/' . $this->id;
    }

    // связи
    // статусы
    public function status()
    {
        return $this->belongsTo(TodoStatus::class, 'status_id')->withDefault(['name' => 'Не определено']);
    }

    // со всей  таблицей этапов и фамилий pivot
    public function todostagefiopivot()
    {
        return $this->hasMany(TodoStageFioPivot::class, 'todo_id');
    }

    // с этапами через сводную таблицу pivot. У одной задачи - несколько этапов
    public function stages()
    {
        return $this->belongsToMany(TodoStage::class, 'todo_stage_fio_pivots', 'todo_id', 'stage_id');
    }

    // с фио через сводную таблицу pivot. У одной задачи - несколько этапов
    public function fio()
    {
        return $this->belongsToMany(Fio::class, 'todo_stage_fio_pivots', 'todo_id', 'fio_id');
    }
}

