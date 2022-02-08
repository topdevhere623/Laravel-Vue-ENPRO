<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;
use App\Traits\ImageTrait;

// модели
use App\Models\Towerkind;
use App\Models\Towermaterial;
use App\Models\Towerconstructionpivot;

// модель (ts сам создал)
class Towerinfo extends Model
{
    // подключение трайтов
    use CommonTrait;
    use ImageTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "towerinfo";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];
    // преобразовани атрибутов
    protected $casts = [];

    // мои атрибуты модели
    const title1 = "Марка опоры";
    const title2 = "Марки опор";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/towerinfo/' . $this->id;
    }

    // связи
    // c материалами опор
    public function towermaterial()
    {
        return $this->belongsTo(Towermaterial::class, 'towermaterial_id')->withDefault();
    }

    // c назначением опор
    public function towerkind()
    {
        return $this->belongsTo(Towerkind::class, 'towerkind_id')->withDefault();
    }

    // с компонентами (сводной) - как родитель
    public function towerconstructions()
    {
        return $this->hasMany(Towerconstructionpivot::class, 'towerinfo_id');
    }
}
