<?php

namespace App\Models;

use App\Traits\BootSaveTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\AclineStatus;
use App\Models\Aclinesegment;
use App\Models\Asset;
use App\Models\Connector;
use App\Models\Identifiedobject;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;

/**
 * Class Acline
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property int $id
 * @property \App\Models\Asset $asset
 */

class Acline extends BaseModel
{
    // использование мягкого удаления
    use SoftDeletes;
    use BootSaveTrait;
    use NestedUpdatable;

    // управляемая таблица
    protected $table = "acline";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "ЛЭП";
    const title2 = "ЛЭП";

    /**
     * @var Asset
     */
    public $asset = null;

    protected $selfIdent = false;

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/acline/' . $this->id;
    }

    // связи
    // со статусами
    public function aclinestatus()
    {
        return $this->belongsTo(AclineStatus::class, 'status_id')->withDefault();
    }

    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // c сегментами
    public function aclinesegments()
    {
        return $this->hasMany(Aclinesegment::class, 'acline_id');
    }

    // с фидером
    public function connector()
    {
        return $this->belongsTo(Connector::class, 'connector_id')->withDefault();
    }

    // с общими данными Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id')->withDefault();
    }

    public function getACLine()
    {
        return $this;
    }
}
