<?php

namespace App\Models;

use App\Contracts\CIM\Wires\ConductingEquipmentInterface;
use App\Traits\ConductingEquipmentTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConductingEquipment
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $equipment_id
 * @property int $basevoltage_id
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment whereBaseVoltageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConductingEquipment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConductingEquipment extends Model implements ConductingEquipmentInterface
{
    use ConductingEquipmentTrait;
    public $equipment = null;
    public $terminals = [];
    public $baseVoltage = null;

    public function getConductingEquipment() : ConductingEquipment
    {
        return $this;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ConductingEquipment $model) {
            $model->getEquipment()->save();
            $model->equipment()->associate($model->getEquipment());
            if($model->getBaseVoltage()) {
                $model->getBaseVoltage()->save();
                $model->baseVoltage()->associate($model->getBaseVoltage());
            }
            if($model->getTerminals()) {
                $model->terminals()->saveMany($model->getTerminals());
            }
            $model->additionalEquipmentContainer()->saveMany($model->getAdditionalEquipmentContainer());
        });

        static::deleted(function (ConductingEquipment $model) {
            $model->getEquipment()->delete();
        });

        static::saving(function (ConductingEquipment $model) {
            $model->getEquipment()->save();
            if($model->getBaseVoltage()) {
                $model->getBaseVoltage()->save();
                $model->baseVoltage()->associate($model->getBaseVoltage());
            } else {
                $model->baseVoltage()->dissociate();
            }
            if($model->getTerminals()) {
                $model->terminals()->saveMany($model->getTerminals());
            }
        });
    }

}
