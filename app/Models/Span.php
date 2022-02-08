<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;
use App\Traits\ImageTrait;

// модели
use App\Models\Identifiedobject;
use App\Models\Aclinesegment;
use App\Models\Crossing;
use App\Models\Customer;
use App\Models\Tower;

// модель
class Span extends Model
{
    // подключение трайтов
    use CommonTrait;
    use ImageTrait;

    // использование мягкого удаления
    use SoftDeletes;

    // управляемая таблица
    protected $table = "span";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Пролет";
    const title2 = "Пролеты";

    // динамические поля
    protected $appends = ['startName', 'endName', 'aclinesObject'];

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/span/' . $this->id;
    }

    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с сегментом
    public function aclinesegment()
    {
        return $this->belongsTo(Aclinesegment::class, 'aclinesegment_id')->withDefault();
    }

    // с IO (начало) !!! Указать только таблицу опор нельзя, т.к. в точке может быть и ТП, и Потребитель. Поэтому IO
    public function startIO()
    {
        return $this->belongsTo(Identifiedobject::class, 'startIO_id')->withDefault();
    }

    // с IO (конец) !!! Указать только таблицу опор нельзя, т.к. в точке может быть и ТП, и Потребитель. Поэтому IO
    public function endIO()
    {
        return $this->belongsTo(Identifiedobject::class, 'endIO_id')->withDefault();
    }

    // с пересечением местности
    public function crossings()
    {
        return $this->hasMany(Crossing::class, 'span_id');
    }

    // дополнительное динамическое поле в запросе
    // имя вершины start
    public function getStartNameAttribute()
    {
        return self::peakName($this->startIO);
    }

    // дополнительное динамическое поле в запросе
    // имя вершины end
    public function getEndNameAttribute()
    {
        return self::peakName($this->endIO);
    }

    // имя вершины (берет либо "name", либо "localname", либо "address" в зависимости от типа)
    // используется чуть выше в динамических полях
    public function peakName($getPeakIO)
    {
        // по-умолчанию для опор - вернуть localname
        $myReturn = $getPeakIO->localname;

        // проверить на Потребителя
        $find = Customer::select('id')->where('identifiedobject_id', $getPeakIO->id)->get();
        if ($find and count($find) > 0) {
            // это Потребитель - вернуть address
            $myReturn = $getPeakIO->address;
        } else {
            // проверить на фиктивное ТП
            $find = Tower::select('id')->where('identifiedobject_id', $getPeakIO->id)->where('fict_tp', 1)->get();
            if ($find and count($find) > 0) {
                // это фиктивная опора - вернуть name
                $myReturn = $getPeakIO->name;
            }
        }

        // возвращаемый параметр
        return $myReturn;
    }

    // дополнительное динамическое поле в запросе
    // возвращает обьект линий, где участвует
    public function getAclinesObjectAttribute()
    {
        // по-умолчанию
        $myReturnCount = 0;
        $myReturnText = null;
        $myReturnIDs = [];

        // его сегмент
        $segments = Aclinesegment::select('acline_id')->where('id', $this->aclinesegment_id)->pluck('acline_id');
        if ($segments and count($segments) > 0) {
            // сегменты с таким пролетом есть
            // его линия
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

                        // возвращаемый тест с названиями линий
                        // в начале очередерй линии
                        if ($myReturnCount > 1) {
                            // дописать нумерацию, если несколько линий
                            $myReturnText .= ($key + 1) . ') ';
                        }
                        $myReturnText .= $acline->identifiedobject->name . ';';
                        // в конце очередерй линии
                        if ($key != end($aclines)) {
                            // дописать пробел, если не последняя
                            $myReturnText .= ' ';
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
}

