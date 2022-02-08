<?php

namespace App\Models;

use App\Contracts\CIM\Wires\ACLineSegmentInterface;
use App\Traits\ACLineSegmentTrait;
use App\Traits\BootSaveTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Identifiedobject;
use App\Models\Acline;
use App\Models\Span;
use App\Models\Aclinesegmentinfo;
use App\Models\Layingconditionkind;

// модель (ts сам создал)
class Aclinesegment extends Model implements ACLineSegmentInterface
{
    // подключение трайтов
    use CommonTrait;

    // использование мягкого удаления
    use SoftDeletes;

    use ACLineSegmentTrait;
    use BootSaveTrait;

    // управляемая таблица
    protected $table = "aclinesegment";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "Сегмент линии";
    const title2 = "Сегменты линий";

    // динамические поля
    protected $appends = ['aclinesObject'];

    /**
     * @var Susceptance
     */
    public $b0ch = null;
    /**
     * @var Susceptance
     */
    public $bch = null;

    /**
     * @var Conductance
     */
    public $g0ch = null;
    /**
     * @var Conductance
     */
    public $gch = null;

    /**
     * @var Resistance
     */
    public $r = null;
    /**
     * @var Resistance
     */
    public $r0 = null;

    /**
     * @var Reactance
     */
    public $x = null;
    /**
     * @var Reactance
     */
    public $x0 = null;

    /**
     * @var Temperature
     */
    public $shortCircuitEndTemperature = null;

    /**
     * @var array
     */
    public $clamp = [];

    /**
     * @var Conductor
     */
    public $conductor = null;

    protected $bootFields = [
        ['Conductor','conductor','belongs','delete'],
        ['B0ch','b0ch','belongs'],
        ['Bch','bch','belongs'],
        ['G0ch','g0ch','belongs'],
        ['Gch','gch','belongs'],
        ['R','r','belongs'],
        ['R0','r0','belongs'],
        ['X','x','belongs'],
        ['X0','x0','belongs'],
        ['ShortCircuitEndTemperature','shortCircuitEndTemperature','belongs'],
        ['Clamp','clamp','hasone', 'delete', 'aclineSegment']
    ];

    protected $selfIdent = true;

    public function getACLineSegment() : Aclinesegment
    {
        return $this;
    }


    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/aclinesegment/' . $this->id;
    }

    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с ЛЭП
    public function acline()
    {
        return $this->belongsTo(Acline::class, 'acline_id')->withDefault();
    }

    // c пролетами
    public function spans()
    {
        return $this->hasMany(Span::class, 'aclinesegment_id');
    }

    // с маркой провода
    public function wiremark()
    {
        return $this->belongsTo(Aclinesegmentinfo::class, 'wiremark_id')->withDefault();
    }

    // с условией прокладки
    public function layingcondition()
    {
        return $this->belongsTo(Layingconditionkind::class, 'layingcondition_id')->withDefault();
    }

    // дополнительное динамическое поле в запросе
    // возвращает обьект линий, где участвует
    public function getAclinesObjectAttribute()
    {
        // по-умолчанию
        $myReturnCount = 0;
        $myReturnText = null;
        $myReturnIDs = [];

        // его линия
        $aclines = Acline::select(['id', 'identifiedobject_id'])->where('id', $this->acline_id)->get();
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

        // возвращаемый параметр
        return [
            'count' => $myReturnCount,
            'text' => $myReturnText,
            'IDs' => $myReturnIDs,
        ];
    }

}
