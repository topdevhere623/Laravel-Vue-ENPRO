<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Tasktype;
use App\Models\User;
use App\Models\Substation;
use App\Models\Connector;
use App\Models\Endpoint;
use App\Models\File;

// модель
class Task extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "task";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Задача";
    const title2 = "Задачи";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/task/' . $this->id;
    }

    // связи
    // с типом задачи
    public function tasktype()
    {
        return $this->belongsTo(Tasktype::class, 'tasktype_id')->withDefault(['title' => 'Не определено']);
    }

    // с пользователем
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault(['username' => 'Не определено']);
    }

    // с подстанцией. У одной задачи - одна подстанция
    public function substation()
    {
        //return $this->hasOne(Substation::class);
        return $this->belongsTo(Substation::class, 'substation_id')->withDefault();
    }

    // с фидерами (коннектор). У одной задачи - один коннектор
    public function connector()
    {
        return $this->belongsTo(Connector::class, 'connector_id')->withDefault();
    }

    // с файлами через промежуточную таблицу. У одной задачи - несколько файлов
    public function file()
    {
        return $this->belongsToMany(File::class, 'task_file_pivots', 'task_id', 'file_id');
    }
}

