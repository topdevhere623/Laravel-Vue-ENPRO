<?php


namespace App\DTO;

use App\Models\Asset;


/**
 * Class AssetDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $type
 * @property string $utc_number
 * @property string $serialnumber
 * @property string $lot_number
 * @property float $purchaseprice
 * @property string $electronic_address
 * @property int $initialcondition
 * @property float $initiallossoflife
 * @property int $enpro_class_defect_id
 * @property array $class_object
 * @property AllKindDTO $kind
 * @property IdentifiedObjectDTO $IdentifiedObject
 * @property AssetGroupDTO $AssetGroups
 */
class AssetDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Asset $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->IdentifiedObject = (! empty($model->IdentifiedObject)) ? IdentifiedObjectDTO::instance()->load($model->IdentifiedObject) : null;
        $this->type = $model->type;
        $this->utc_number = $model->utc_number;
        $this->serialnumber = $model->serialnumber;
        $this->lot_number = $model->lot_number;
        $this->purchaseprice = $model->purchaseprice;
        $this->electronic_address = $model->electronic_address;
        $this->initialcondition = $model->initialcondition;
        $this->initiallossoflife = $model->initiallossoflife;
        $this->enpro_class_defect_id = $model->enpro_class_defect_id;
        $this->kind = empty($model->kind) ? null : AllKindDTO::instance()->load($model->kind);
        $this->AssetGroups = empty($model->AssetGroups) ? null : $model->AssetGroups->map(function($q) {return AssetGroupDTO::instance()->load($q);});

        $classObject = null;
        if (! empty($model->IdentifiedObject->class_object)) {
            $classId = (int)explode(':', $model->IdentifiedObject->class_object)[1];
            $className  = explode('\\', explode(':', $model->IdentifiedObject->class_object)[0])[2];
            $classObject = ['id' => $classId, 'name' => $className];
        }
        $this->class_object = $classObject;
        return $this;
    }

    /**
     * @param \App\Models\Asset $model
     * @return $this
     * @throws \Exception
     */
    public function loadToir($model)
    {
        $this->mrid = (! empty($model->IdentifiedObject)) ? $model->IdentifiedObject->getMRID() : null;
        $this->name = (! empty($model->IdentifiedObject)) ?  $model->IdentifiedObject->name : null;
        return $this;
    }
}
