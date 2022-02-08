<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;
use App\Traits\ImageTrait;

// модели
use App\Models\Asset;
use App\Models\Identifiedobject;
use App\Models\Endpoint;

// модель
class Connector extends Model
{
    // подключение трайтов
    use CommonTrait;
    use ImageTrait;
    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "connector";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Фидер";
    const title2 = "Фидеры";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/connector/' . $this->id;
    }

    // связи
    // с общими данными Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id')->withDefault();
    }

    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с конечными точками
    public function endpoint()
    {
        return $this->hasMany(Endpoint::class, 'connector_id');
    }

//    // с файлами через промежуточную таблицу. У одного коннектора - несколько файлов
//    public function file()
//    {
//        return $this->belongsToMany(File::class, 'connector_file_pivots', 'connector_id', 'file_id');
//    }
}
