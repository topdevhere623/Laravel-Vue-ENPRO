<?php

namespace App\Models;

use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Traits\IdentifiedObjectTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Czim\NestedModelUpdater\Traits\NestedUpdatable;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Acline;
use App\Models\Aclinesegment;
use App\Models\Busbarsection;
use App\Models\Connector;
use App\Models\Disconnector;
use App\Models\Discharger;
use App\Models\Terminal;
use App\Models\Tower;
use App\Models\Span;

// модель

/**
 * App\Models\Identifiedobject
 *
 * @property int $id
 * @property int|null $classname_id
 * @property int|null $subclass_id
 * @property int|null $voltage_id
 * @property int|null $asset_id
 * @property int|null $enobj_id
 * @property int|null $subcontrollarea_id
 * @property int|null $bay_id
 * @property int|null $role_id
 * @property int|null $connector_id
 * @property string|null $keylink
 * @property string|null $description
 * @property string|null $localname Диспетчерский номер
 * @property string|null $name Диспетчерское имя
 * @property string|null $eqcid
 * @property float|null $lat Широта
 * @property float|null $long Долгота
 * @property int|null $entitytype
 * @property int|null $group toDo
 * @property int|null $phaseno
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $address
 * @property string $aliasname
 * @property-read Acline $acline
 * @property-read Aclinesegment|null $aclinesegment
 * @property-read \App\Models\Asset|null $asset
 * @property-read \App\Models\BaseVoltage|null $basevoltage
 * @property-read \App\Models\Bay|null $bay
 * @property-read Busbarsection|null $busbarsection
 * @property-read \App\Models\Classname|null $classname
 * @property-read Connector|null $connector
 * @property-read \App\Models\Customer|null $customer
 * @property-read Discharger|null $discharger
 * @property-read Disconnector|null $disconnector
 * @property-read \App\Models\Endpoint|null $endpoint
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Name[] $names
 * @property-read int|null $names_count
 * @property-read \App\Models\Role|null $role
 * @property-read Span|null $span
 * @property-read \App\Models\Subclass|null $subclass
 * @property-read \App\Models\Subcontrolarea $subcontrolarea
 * @property-read \App\Models\Substation|null $substation
 * @property-read Terminal|null $terminal
 * @property-read Tower|null $tower
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject newQuery()
 * @method static \Illuminate\Database\Query\Builder|Identifiedobject onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject query()
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereAliasname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereBayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereClassnameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereConnectorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereEnobjId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereEntitytype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereEqcid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereKeylink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereLocalname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject wherePhaseno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereSubclassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereSubcontrollareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereVoltageId($value)
 * @method static \Illuminate\Database\Query\Builder|Identifiedobject withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Identifiedobject withoutTrashed()
 * @mixin \Eloquent
 * @property string $mrid
 * @method static \Illuminate\Database\Eloquent\Builder|Identifiedobject whereMrid($value)
 */
class Identifiedobject extends Model implements IdentifiedObjectInterface
{
    // подключение трайтов
    use CommonTrait;
    use NestedUpdatable;
    // использование мягкого удаления
    use SoftDeletes;

    /* CIM EIX61970 Package Core */
    use IdentifiedObjectTrait;

    // управляемая таблица
    protected $table = "identifiedobject";

    // список полей, разрешенных на редактирование
    protected $fillable = ['mrid', 'name' ,'description'];
    // список полей запрещенных на редактирование
    //protected $guarded = [];
    // скрытые поля
    //protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Общие технические данные IO";
    const title2 = "Общие технические данные IO";

    public function getIdentifiedObject()
    {
        return $this;
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function(Identifiedobject $model){
            $model->getIdentifiedObject()->getMRID();
        });
        self::created(function(Identifiedobject $model){
            $model->names()->saveMany($model->getNames());
        });
        self::saving(function(Identifiedobject $model){
            if(!$model->getIdentifiedObject()->mrid) $model->getMRID();
            $model->names()->saveMany($model->getNames());
        });
    }

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/identifiedobject/' . $this->id;
    }

    // связи
    // с connector
    public function connector()
    {
        return $this->belongsTo(Connector::class, 'connector_id')->withDefault(['Не определено']);
    }

    // обратная связь один-к-одному
    public function acline()
    {
        return $this->hasOne(Acline::class)->withDefault('-');
    }

    // с сегментами
    public function aclinesegment()
    {
        return $this->hasOne(Aclinesegment::class);
    }

    // с пролетами/участками
    public function span()
    {
        return $this->hasOne(Span::class);
    }

    // с опорами
    public function tower()
    {
        return $this->hasOne(Tower::class);
    }

    // с подстанциями
    public function substation()
    {
        return $this->hasOne(Substation::class);
    }

    // с секциями шин
    public function busbarsection()
    {
        return $this->hasOne(Busbarsection::class);
    }

    // с приводами переключателей
    public function terminal()
    {
        return $this->hasOne(Terminal::class);
    }

    // с потребителями
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    // с конечными точками
    public function endpoint()
    {
        return $this->hasOne(Endpoint::class);
    }

    // с разьединителями
    public function disconnector()
    {
        return $this->hasOne(Disconnector::class);
    }

    // с разрядниками
    public function discharger()
    {
        return $this->hasOne(Discharger::class);
    }

    // с classname
    public function classname()
    {
        return $this->belongsTo(Classname::class, 'classname_id')->withDefault(['Не определено']);
    }

    // с базовым напряжение (с basevoltage связал, voltage - нет такой таблицы)
    public function basevoltage()
    {
        return $this->belongsTo(BaseVoltage::class, 'voltage_id')->withDefault(['Не определено']);
    }

    // с subclasses
    public function subclass()
    {
        return $this->belongsTo(Subclass::class, 'subclass_id')->withDefault(['Не определено']);
    }

    // с общими данными Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id')->withDefault(['Не определено']);
    }

    // с enobj (нет такой таблицы!!!)
    public function enobj()
    {
        //return $this->belongsTo(Enobj::class, 'enobj_id')->withDefault(['Не определено']);
        return 'Не определено';
    }

    // с subcontrolarea
    public function subcontrolarea()
    {
        return $this->belongsTo(Subcontrolarea::class, 'subcontrolarea_id')->withDefault(['Не определено']);
    }

    // с bay
    public function bay()
    {
        return $this->belongsTo(Bay::class, 'bay_id')->withDefault(['Не определено']);
    }

    // с role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->withDefault(['Не определено']);
    }

    // с прикрепленными файлами
    public function files()
    {
        return $this->belongsToMany(File::class, 'identifiedobject_has_file', 'io_id', 'file_id');
    }

    public function names() : HasMany
    {
        return $this->hasMany(Name::class, 'identifiedobject_id', 'id');
    }

    // дистанция от указанной точки (временно здесь ts)
    public function getDistance($getLat, $getLong)
    {
        //return static(((acos(sin(($getLat * pi() / 180)) * sin((($this->attributes['lat']) * pi() / 180)) + cos(($getLat * pi() / 180)) * cos((($this->attributes['lat']) * pi() / 180)) * cos((($getLong - ($this->attributes['long'])) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515);
        // $getLat
        // $getLong
        // $this->attributes['lat']
        // $this->attributes['long']
    }
}
