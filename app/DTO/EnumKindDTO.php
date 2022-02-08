<?php


namespace App\DTO;


/**
 * Class EnumKindDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $literal
 * @property string|integer $value
 * @property string $ru_value
 * @property string $enpro_code
 * @property string|longText $description
 *
 */
class EnumKindDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\BaseModel $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->literal = $model->literal;
        $this->value = $model->value;
        $this->ru_value = $model->ru_value;
        $this->enpro_code = $model->enpro_code;
        $this->description = $model->description;
        return $this;
    }

}
