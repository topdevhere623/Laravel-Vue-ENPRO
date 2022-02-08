<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Connector;
use App\Models\File;

// ts новая модель для связи коннекторов с файлами
class ConnectorFilePivot extends Model
{
    // подключение трайтов
    use CommonTrait;

    // управляемая таблица
    protected $table = "connector_file_pivots";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Файлы коннекторов (сводная)";
    const title2 = "Файлы коннекторов (сводная)";

    // связи
}

