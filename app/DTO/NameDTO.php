<?php


namespace App\DTO;

use App\Models\Name;

/**
 * Class NameDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $name
 *
 */
class NameDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Name $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->name = $model->name;

        return $this;
    }
}
