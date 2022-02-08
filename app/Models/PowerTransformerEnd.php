<?php

namespace App\Models;

use App\Contracts\CIM\Wires\PowerTransformerEndInterface;
use App\Contracts\CIM\Wires\PowerTransformerInterface;
use App\Traits\TransformerEndTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
/**
 * App\Models\PowerTransformerEnd
 *
 * @property int $id
 * @property int|null $endnumber
 * @property int $powertransformer_id
 * @property int $basevoltage_id
 * @property float|null $DVOLTAGE_NN1
 * @property float|null $DVOLTAGE_NN2
 * @property float|null $DVOLTAGE_NN3
 * @property float|null $DVOLTAGE_NN4
 * @property float|null $DVOLTAGE_NN5
 * @property float|null $RATEI
 * @property int $insulatormark_id
 * @property int|null $INSULATORNO
 * @property int|null $HAVEZERO
 * @property int $insulator_id
 * @property string|null $WINDINGCONNECTION
 * @property float|null $UNOM
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $transformer_ends_id
 * @property float $b
 * @property float $b0
 * @property float $g
 * @property float $g0
 * @property int $phaseangleclock
 * @property int|null $connectionkind
 * @property float $rateds
 * @property float $ratedu
 * @property float $r
 * @property float $r0
 * @property float $x
 * @property float $x0
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd newQuery()
 * @method static \Illuminate\Database\Query\Builder|PowerTransformerEnd onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd query()
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereB0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereBaseVoltageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereConnectionkind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereDVOLTAGENN1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereDVOLTAGENN2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereDVOLTAGENN3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereDVOLTAGENN4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereDVOLTAGENN5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereEndnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereG0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereHAVEZERO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereINSULATORNO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereInsulatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereInsulatormarkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd wherePhaseangleclock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd wherePowertransformerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereRATEI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereRateds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereRatedu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereTransformerEndsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereUNOM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereWINDINGCONNECTION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PowerTransformerEnd whereX0($value)
 * @method static \Illuminate\Database\Query\Builder|PowerTransformerEnd withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PowerTransformerEnd withoutTrashed()
 * @mixin \Eloquent
 */
class PowerTransformerEnd extends Model implements PowerTransformerEndInterface
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    use TransformerEndTrait;

    // управляемая таблица
    /**
     * @var string
     */
    protected $table = "powertransformerend";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    protected $transformerEnd = null;

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            // ... code here
            $model->getTransformerEnd()->save();
            $model->setTransformerEnd($model->getTransformerEnd());
        });
    }


    /**
     * @return TransformerEnd
     */
    public function getTransformerEnd()  : TransformerEnd
    {
        if($this->transformerEnd) return $this->transformerEnd;
        $this->transformerEnd = $this->transformerend()->get()->get(0);
        if(!($this->transformerEnd instanceof TransformerEnd)) $this->transformerEnd = new TransformerEnd();
        return $this->transformerEnd;
    }

    public function setTransformerEnd(TransformerEnd $transformerEnd) : void
    {
        $this->transformerend()->associate($transformerEnd);
    }

    public function transformerend()
    {
        return $this->belongsTo(TransformerEnd::class, 'transformer_ends_id');
    }

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/powertransformerend/' . $this->id;
    }

    public function getB(): float
    {
        return $this->b;
    }
    public function setB(float $b) : void
    {
        $this->b = $b;
    }

    public function getB0(): float
    {
        return $this->b0;
    }
    public function setB0(float $b0) : void
    {
        $this->b0 = $b0;
    }

    public function connectionkind()
    {
        return $this->belongsTo(WindingConnection::class);
    }

    public function setConnectionKind( WindingConnection $windingConnection) : void
    {
        $this->connectionkind()->associate($windingConnection);
    }
    public function getConnectionKind() : WindingConnection
    {
        if($this->connectionkind()->get()->get(0)
            instanceof
            WindingConnection) return $this->connectionkind()->get()->get(0);
        else return new WindingConnection();
    }

    public function getG(): float
    {
        return $this->g;
    }
    public function setG(float $g) : void
    {
        $this->g = $g;
    }

    public function getG0(): float
    {
        return $this->g0;
    }
    public function setG0(float $g0) : void
    {
        $this->g0 = $g0;
    }

    public function setPhaseAngleClock(int $phaseAngleClock) : void
    {
        $this->phaseangleclock = $phaseAngleClock;
    }
    public function getPhaseAngleClock() : int
    {
        return $this->phaseangleclock;
    }

    public function getR(): float
    {
        return $this->r;
    }
    public function setR(float $r) : void
    {
        $this->r = $r;
    }

    public function getR0(): float
    {
        return $this->r0;
    }
    public function setR0(float $r0) : void
    {
        $this->r0 = $r0;
    }

    public function getRatedS(): float
    {
        return $this->rateds;
    }
    public function setRatedS(float $ratedS) : void
    {
        $this->rateds = $ratedS;
    }

    public function getRatedU(): float
    {
        return $this->ratedu;
    }
    public function setRatedU(float $ratedU) : void
    {
        $this->ratedu = $ratedU;
    }

    public function getX(): float
    {
        return $this->x;
    }
    public function setX(float $x) : void
    {
        $this->x = $x;
    }

    public function getX0(): float
    {
        return $this->x0;
    }
    public function setX0(float $x0) : void
    {
        $this->x0 = $x0;
    }

    // связи
}
