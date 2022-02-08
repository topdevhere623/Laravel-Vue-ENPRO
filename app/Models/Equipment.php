<?php

namespace App\Models;

use App\Contracts\CIM\Wires\EquipmentInterface;
use App\Traits\EquipmentTrait;
use App\Traits\PowerSystemResourceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Equipment
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $aggregate
 * @property int $inService
 * @property int $networkAnalysisEnabled
 * @property int $normallyInService
 * @property int $power_system_resources_id
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereAggregate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereInService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNetworkAnalysisEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereNormallyInService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment wherePowerSystemResourcesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Equipment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Equipment extends Model implements EquipmentInterface
{
    use EquipmentTrait;
    public $psr = null;
    public $equipmentContainer = null;
    public $addEquipmentContainers = [];
    public function getEquipment() : Equipment
    {
        return $this;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Equipment $model) {
            $model->getPowerSystemResource()->save();
            $model->powersystemresource()->associate($model->getPowerSystemResource());
            if($model->getEquipmentContainer()) {
                $model->getEquipmentContainer()->save();
                $model->equipmentContainer()->dissociate();
                $model->equipmentContainer()->associate($model->getEquipmentContainer());
            };
            $model->additionalEquipmentContainer()->detach();
            $model->additionalEquipmentContainer()->saveMany($model->getAdditionalEquipmentContainer());
        });

        static::deleted(function (Equipment $model) {
            $model->getPowerSystemResource()->delete();
        });

        static::saving(function (Equipment $model) {
            $model->getPowerSystemResource()->save();
            if($model->getEquipmentContainer()) {
                $model->getEquipmentContainer()->save();
                $model->equipmentContainer()->dissociate();
                $model->equipmentContainer()->associate($model->getEquipmentContainer());
            };
            $model->additionalEquipmentContainer()->detach();
            $model->additionalEquipmentContainer()->saveMany($model->getAdditionalEquipmentContainer());
        });
    }



    //
}
