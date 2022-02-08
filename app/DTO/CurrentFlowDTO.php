<?php


namespace App\DTO;

use App\Models\CurrentFlow;



/**
 * Class CurrentFlowDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property float $value
 * @property UnitMultiplierDTO $multiplier
 * @property UnitSymbolsDTO $unit

 *
 */
class CurrentFlowDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\CurrentFlow $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->value = $model->value;
        $this->multiplier = (! empty($model->multiplier)) ? UnitMultiplierDTO::instance()->load($model->multiplier) : null;
        $this->unit = (! empty($model->unit)) ? UnitSymbolsDTO::instance()->load($model->unit) : null;
        return $this;
    }

    /**
     * @param \App\Models\CurrentFlow $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);

        return $this;
    }
}
