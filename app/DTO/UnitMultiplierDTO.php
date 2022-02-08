<?php


namespace App\DTO;

use App\Models\UnitMultiplier;



/**
 * Class UnitMultiplierDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property integer $value
 * @property string $literal
 * @property string $description


 *
 */
class UnitMultiplierDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\UnitMultiplier $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->value = $model->value;
        $this->literal = $model->literal;
        $this->description = $model->description;

        return $this;
    }

    /**
     * @param \App\Models\UnitMultiplier $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);

        return $this;
    }
}
