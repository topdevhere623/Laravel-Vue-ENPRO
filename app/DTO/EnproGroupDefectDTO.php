<?php


namespace App\DTO;

use App\Models\EnproGroupDefect;



/**
 * Class EnproGroupDefectDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $code_group
 * @property string $title


 *
 */
class EnproGroupDefectDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\EnproGroupDefect $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->code_group = $model->code_group;
        $this->title = $model->title;


        return $this;
    }
}
