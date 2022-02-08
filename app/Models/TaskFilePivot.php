<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Task;
use App\Models\File;

// ts новая модель для связи задач с файлами
class TaskFilePivot extends Model
{
    // подключение трайтов
    use CommonTrait;

    // управляемая таблица
    protected $table = "task_file_pivots";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Файл задачи (сводная)";
    const title2 = "Файл задач (сводная)";

    // связи
}

