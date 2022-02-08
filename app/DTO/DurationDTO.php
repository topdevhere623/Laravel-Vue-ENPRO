<?php


namespace App\DTO;

use App\Models\Duration;

use App\DTO\UnitMultiplierDTO;
use App\DTO\UnitSymbolsDTO;


/**
 * Class DurationDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property array|null $value
 *
 */
class DurationDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Duration $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->value = $model->value;

        return $this;
    }

}
