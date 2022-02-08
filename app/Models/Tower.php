<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;
use App\Traits\ImageTrait;

// модели
use App\Models\Acline;
use App\Models\Aclinesegment;
use App\Models\Connectivitycode;
use App\Models\Disconnector;
use App\Models\Discharger;
use App\Models\Identifiedobject;
use App\Models\Towermaterial;
use App\Models\Towerkind;
use App\Models\Towerconstructionkind;
use App\Models\Towerinfo;
use App\Models\Span;
use App\Models\Name;

// модель
class Tower extends Model
{
    // подключение трайтов
    use CommonTrait;
    use ImageTrait;

    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "tower";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Опора";
    const title2 = "Опоры";

    // динамические поля
    protected $appends = ['aclinesObject', 'IOObject', 'towerMaterialObject', 'towerInfoObject', 'towerKindObject', 'towerConstructionObject', 'nameObject', 'lat', 'long']; // 'viewName',

    /**
     * @var array
     */
    protected $coordinates = [];// [lat, long]

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/tower/' . $this->id;
    }

    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с материалами опор
    public function towermaterial()
    {
        return $this->belongsTo(Towermaterial::class, 'towermaterial_id')->withDefault();
    }

    // с назначение опор
    public function towerkind()
    {
        return $this->belongsTo(Towerkind::class, 'towerkind_id')->withDefault();
    }

    // с конструкциями опор
    public function towerconstruction()
    {
        return $this->belongsTo(Towerconstructionkind::class, 'towerconstructionkind_id')->withDefault();
    }

    // с марками опор
    public function towerinfo()
    {
        return $this->belongsTo(Towerinfo::class, 'towerinfo_id')->withDefault();
    }

    // с кодом подключения
    public function connectivitycode()
    {
        return $this->belongsTo(Connectivitycode::class, 'connectivitycode_id')->withDefault();
    }

    // компоненты в реальных опорах (сводная) - как родитель
    public function towerconstructionreals()
    {
        return $this->hasMany(Towerconstructionrealpivot::class, 'tower_id');
    }

    // -----------------------------------------------------------------------
    // дополнительное динамическое поле в запросе
    // возвращает names (все имеющиеся имена)
    public function getNameObjectAttribute()
    {
        return $this->identifiedobject->names;
    }

    // метод получение имени опоры для конкретной опоры
    // если опора участвует в разных линиях, то имен может быть несолько
    public function getName($getAclineID = null)
    {
        $myReturn = null;

        if ($getAclineID == null) {
            // id линии не передали

            // 1) сперва смотрим все имена в Names
            $myNames = Name::where('identifiedobject_id', $this->identifiedobject_id)->get();
            if ($myNames) {
                // имя(-ена) в Names нашлось(-ись)
                $i = 0;
                foreach ($myNames as $item) {
                    $i++;
                    $myReturn .= 'Имя №' . $i . ': ' . $item->name . '; ';
                }
            } else {

                // 2) тогда смотрим в IO, как раньше
                // возвращает имя [name] или [localname] - в завимости от того, фиктивное это ТП или нет
                if ($this->fict_tp == 1) {
                    // это фиктивная опора
                    $myReturn = $this->identifiedobject->name;
                } else {
                    // это просто опора
                    $myReturn = $this->identifiedobject->localname;
                }
            }
        } else {
            // id линии передали

            // 3) сперва смотрим в Names
            $myName = Name::where('identifiedobject_id', $this->identifiedobject_id)->where('object_id', $getAclineID)->get()->first();
            if ($myName) {
                // имя в Names нашлось
                $myReturn = $myName->name;
            } else {

                // 4) тогда смотрим в IO, как раньше
                // возвращает имя [name] или [localname] - в завимости от того, фиктивное это ТП или нет
                if ($this->fict_tp == 1) {
                    // это фиктивная опора
                    $myReturn = $this->identifiedobject->name;
                } else {
                    // это просто опора
                    $myReturn = $this->identifiedobject->localname;
                }
            }
        }

        // возвращаемый параметр
        return $myReturn;
    }

    // метод сохранения имени опоры для конкретной линии
    public function setName($getAclineID, $getNameForSave)
    {
        if ($this->id == null) {
            // id еще нет
            // вставить новую строчку
            $myName = new Name();
            $myName->identifiedobject_id = $this->identifiedobject_id;
            $myName->object_id = $getAclineID;
            $myName->name = $getNameForSave;
            $myName->save();
        } else {
            // id есть - обновить существующую
            $myName = Name::where('identifiedobject_id', $this->identifiedobject_id)->where('object_id', $getAclineID)->get()->first();
            if ($myName) {
                // строчка нашлась - обновить
                $myName->name = $getNameForSave;
                $myName->save();
            } else {
                // строчка не нашлась
                // вставить новую строчку
                $myName = new Name();
                $myName->identifiedobject_id = $this->identifiedobject_id;
                $myName->object_id = $getAclineID;
                $myName->name = $getNameForSave;
                $myName->save();
            }
        }

        // в IO (где хранил раньше) очистить
        $IOsave = $this->identifiedobject;
        $IOsave->name = null;
        $IOsave->localname = null;
        $IOsave->save();

    }

    // -----------------------------------------------------------------------

    // дополнительное динамическое поле в запросе
    // возвращает имя [name] или [localname] - в завимости от того, фиктивное это ТП или нет
    // ЭТО НАДО БУДЕТ УДАЛИИИИИИИИТЬЬЬЬЬЬЬЬЬЬЬ!!!!!!!!!!!!! ЗАМЕНИТЬ ВЕЗЕД НА getName(;%:;№)
//    public function getViewNameAttribute()
//    {
//        if ($this->fict_tp == 1) {
//            // это фиктивная опора
//            return $this->identifiedobject->name;
//        } else {
//            // это просто опора
//            return $this->identifiedobject->localname;
//        }
//    }

    // дополнительное динамическое поле в запросе
    // возвращает identifiedobject
    public function getIOObjectAttribute()
    {
        return $this->identifiedobject;
    }

    // дополнительное динамическое поле в запросе
    // возвращает towermaterial
    public function getTowerMaterialObjectAttribute()
    {
        return $this->towermaterial;
    }

    // дополнительное динамическое поле в запросе
    // возвращает towerkind
    public function getTowerKindObjectAttribute()
    {
        return $this->towerkind;
    }

    // дополнительное динамическое поле в запросе
    // возвращает towerconstruction
    public function getTowerConstructionObjectAttribute()
    {
        return $this->towerconstruction;
    }

    // дополнительное динамическое поле в запросе
    // возвращает towerinfo
    public function getTowerInfoObjectAttribute()
    {
        return $this->towerinfo;
    }

    // дополнительное динамическое поле в запросе
    // возвращает обьект линий, где участвует
    public function getAclinesObjectAttribute()
    {
        // по-умолчанию
        $myReturnCount = 0;
        $myReturnText = null;
        $myReturnIDs = [];

        // ее пролет
        $spans = Span::select('aclinesegment_id')->where('startIO_id', $this->identifiedobject_id)->orWhere('endIO_id', $this->identifiedobject_id)->pluck(('aclinesegment_id'));
        if ($spans and count($spans) > 0) {
            // пролеты с такой опорой есть
            // ее сегменты
            $segments = Aclinesegment::select('acline_id')->whereIn('id', $spans)->pluck('acline_id');
            if ($segments and count($segments) > 0) {
                // сегменты с такой опорой есть
                // ее линии
                $aclines = Acline::select(['id', 'identifiedobject_id'])->whereIn('id', $segments)->get();
                if ($aclines) {
                    // возвращаемое кол-во линий
                    // образец вывода одинаков в tower, span, aclinesegments
                    $myReturnCount = count($aclines);
                    if ($myReturnCount > 0) {
                        // линия(-и) найдена(-ы)
                        // сформировать список
                        foreach ($aclines as $key => $acline) {

                            // возвращаемый массив с id линии
                            array_push($myReturnIDs, $acline->id);

                            // возвращаемый текст с названиями линий
                            // в начале линии
                            if ($myReturnCount > 1) {
                                // дописать нумерацию, если несколько линий
                                $myReturnText .= ($key + 1) . ') ';
                            }
                            $myReturnText .= 'Линия: [' . $acline->identifiedobject->name . '] Имя: [' . $this->getName($acline->id) . '];';
                            // в конце линии
                            if ($key != end($aclines)) {
                                // дописать пробел, если не последняя
                                $myReturnText .= ' ';
                            }
                        }
                    }
                }
            }
        }

        // возвращаемый параметр
        return [
            'count' => $myReturnCount,
            'text' => $myReturnText,
            'IDs' => $myReturnIDs,
        ];
    }

    // дополнительное динамическое поле в запросе
    // возвращает lat
    public function getLatAttribute()
    {
        return $this->identifiedobject->lat;
    }

    // дополнительное динамическое поле в запросе
    // возвращает long
    public function getLongAttribute()
    {
        return $this->identifiedobject->long;
    }

    /**
     * @return array
     * [lat , long] if this is a fict tower return coordinates energoobject of connected terminal
     */
    public function getCoordinates()
    {
        if ($this->coordinates) return $this->coordinates;
        if ($this->fict_tp && $this->connectivitycode_id) {
            /** @var Connectivitycode $connectitivyNode */
            $connectitivyNode = $this->connectivitycode()->get()->get(0);
            $terminals = $connectitivyNode->terminal()->get();
            foreach ($terminals as $terminal) {
                /** @var Identifiedobject $enobj */
                /** @var Terminal $terminal */
                $enobj = Identifiedobject::find($terminal->identifiedobject()->get()->get(0)->enobj_id);
                if ($enobj) {
                    $this->coordinates = [$enobj->lat, $enobj->long];
                    return $this->coordinates;
                }
            }
        }
        $this->coordinates = [
            $this->identifiedobject()->get()->get(0)->lat,
            $this->identifiedobject()->get()->get(0)->long];
        return $this->coordinates;
    }
}
