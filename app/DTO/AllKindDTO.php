<?php


namespace App\DTO;


/**
 * Class AllKindDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string|float $value
 * @property string|float $ru_value
 * @property string $description
 * @property string $enpro_code
 *
 */
class AllKindDTO extends AbstractPublicDTO
{
    /**
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->value = $model->value;
        $this->ru_value = $model->ru_value;
        $this->description = $model->description;
        $this->enpro_code = $model->enpro_code;

        return $this;
    }

}
