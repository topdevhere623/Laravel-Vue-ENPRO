<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class Powertransformer extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    //use Powe

    // управляемая таблица
    protected $table = "powertransformer";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Мощность трансформатора";
    const title2 = "Мощность трансформаторов";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/powertransformer/' . $this->id;
    }



    // связи
}
