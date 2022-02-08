<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

/**
 * Class Gost
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property string $name
 * @property integer $keylink

 */
class Gost extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "gost";

    // список полей, разрешенных на редактирование
    protected $fillable = [
        'name',
        'keylink',
    ];

    protected $casts = [
        'name' => 'string',
        'keylink' => 'string',
    ];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "ГОСТ";
    const title2 = "ГОСТ-ы";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/gost/' . $this->id;
    }

    // связи
}
