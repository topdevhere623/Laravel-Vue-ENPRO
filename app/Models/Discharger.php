<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Identifiedobject;
use App\Models\Dischargerinfo;
use App\Models\Tower;
use App\Models\Span;

// модель
class Discharger extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "discharger";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Разрядник";
    const title2 = "Разрядники";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/discharger/' . $this->id;
    }

    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с марками
    public function dischargerinfo()
    {
        return $this->belongsTo(Dischargerinfo::class, 'dischargerinfo_id')->withDefault();
    }

    // с IO (начало) !!! Указать только таблицу опор нельзя, т.к. в точке может быть и ТП, и Потребитель. Поэтому IO
    public function startIO()
    {
        return $this->belongsTo(Identifiedobject::class, 'startIO_id')->withDefault();
    }

    // с пролетом/участком
    public function span()
    {
        return $this->belongsTo(Span::class, 'span_id')->withDefault();
    }
}
