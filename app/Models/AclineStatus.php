<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// модели
use App\Models\Acline;

// ts новая модель
class AclineStatus extends Model
{
    // управляемая таблица
    protected $table = "acline_status";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];

    // мои атрибуты модели
    const title1 = "Статус линии";
    const title2 = "Статусы линий";

    // связи
    // c линиями
    public function aclines()
    {
        return $this->hasMany(Acline::class, 'status_id');
    }
}
