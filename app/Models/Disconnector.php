<?php

namespace App\Models;

use App\Contracts\CIM\Wires\SwitchInterface;
use App\Traits\BootSaveTrait;
use App\Traits\SwitchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Identifiedobject;
use App\Models\DisconnectorInfo;
use App\Models\Tower;
use App\Models\Span;

// модель
class Disconnector extends Model implements SwitchInterface
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    use SwitchTrait;
    use BootSaveTrait;

    // управляемая таблица
    protected $table = "disconnector";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Разъединитель";
    const title2 = "Разъединители";

    public $switchObject;

    protected $bootFields = [
        ['Switch','switchObject','belongs','delete']
    ];

    protected $selfIdent = true;

    public function getDisconnector() : Disconnector
    {
        return $this;
    }

    public function switchObject() : BelongsTo
    {
        return $this->getDisconnector()->belongsTo(SwitchObject::class, 'switches_id');
    }

    public function getSwitch() : SwitchObject
    {
        if($this->getDisconnector()->switchObject) return $this->getDisconnector()->switchObject;
        $this->getDisconnector()->switchObject = $this->switchObject()->get()->get(0);
        if(!$this->getDisconnector()->switchObject) $this->getDisconnector()->switchObject = new SwitchObject();
        return $this->getDisconnector()->switchObject;
    }

    public function setSwitch(SwitchObject $switchObject) : void
    {
        $this->getDisconnector()->switchObject = $switchObject;
    }

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/disconnector/' . $this->id;
    }

    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с марками
    public function disconnectorinfo()
    {
        return $this->belongsTo(DisconnectorInfo::class, 'disconnectorinfo_id')->withDefault();
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
