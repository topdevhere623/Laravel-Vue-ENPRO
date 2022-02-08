<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Terminal;

// модель
class Connectivitycode extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "connectivitycode";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Код подключения";
    const title2 = "Коды подключения";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/connectivitycode/' . $this->id;
    }

    // связи
    // с приводами переключателей
    public function terminal()
    {
        return $this->hasMany(Terminal::class, 'connectivitycode_id');
    }
}
