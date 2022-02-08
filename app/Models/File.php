<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;
use App\Traits\ImageTrait;

// модели
use App\Models\Picturetype;

// модель
class File extends Model
{
    // подключение трайтов
    use CommonTrait;
    use ImageTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "file";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Файл";
    const title2 = "Файлы";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/file/' . $this->id;
    }

    // связи
    // c типом
    public function picturetype()
    {
        return $this->belongsTo(Picturetype::class, 'picturetype_id');
    }
}
