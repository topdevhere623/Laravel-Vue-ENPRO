<?php


namespace App\DTO;

use App\Models\UnitSymbol;



/**
 * Class UnitSymbolsDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property integer $value
 * @property string $literal
 * @property string $description


 *
 */
class UnitSymbolsDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\UnitSymbol $model
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
     * @param \App\Models\UnitSymbol $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);

        return $this;
    }
}
