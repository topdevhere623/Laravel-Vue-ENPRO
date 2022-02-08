<?php

namespace App\Models;

use App\Contracts\CIM\Wires\BaseVoltageInterface;
use App\Contracts\CIM\Wires\VoltageInterface;
use App\Traits\IdentifiedObjectParentTrait;
use App\Traits\IdentifiedObjectTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели

// модель
class BaseVoltage extends Model implements BaseVoltageInterface
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    use IdentifiedObjectTrait;
    use IdentifiedObjectParentTrait;

    // управляемая таблица
    protected $table = "basevoltage";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    /**
     * @var Voltage
     */
    public $nominal = null;

    // мои атрибуты модели
    const title1 = "Базовое напряжение";
    const title2 = "Базовые напряжения";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/basevoltage/' . $this->id;
    }


    public function nominalVoltage()
    {
        return $this->belongsTo(Voltage::class, 'voltages_id');
    }

    public function getNominalVoltage():? VoltageInterface
    {
        if($this->nominal) return $this->nominal;
        if($this->nominalVoltage()->get()->get(0)) {
            $this->nominal = $this->nominalVoltage()->get()->get(0);
        };
        return $this->nominal;
    }

    public function setNominalVoltage(VoltageInterface $voltage)
    {
        $this->nominal = $voltage;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (BaseVoltage  $model) {
            if($model->getIdentifiedObject()) {
                $model->getIdentifiedObject()->save();
                $model->setIdentifiedObject($model->getIdentifiedObject());
            };
            if($model->getNominalVoltage()) {
                $model->getNominalVoltage()->save();
                $model->nominalVoltage()->associate($model->getNominalVoltage());
            }
        });

        static::deleted(function (BaseVoltage $model) {
            $model->getIdentifiedObject()->delete();;
        });

        static::saving(function (BaseVoltage $model) {
            $model->getIdentifiedObject()->save();
            $model->setIdentifiedObject($model->getIdentifiedObject());
            if($model->getNominalVoltage()) {
                $model->getNominalVoltage()->save();
                $model->nominalVoltage()->associate($model->getNominalVoltage());
            }
        });
    }
}
