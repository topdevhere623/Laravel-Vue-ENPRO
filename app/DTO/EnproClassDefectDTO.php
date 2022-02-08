<?php


namespace App\DTO;

use App\Models\EnproClassDefect;



/**
 * Class EnproClassDefectDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $type
 * @property string $class
 * @property string $title


 *
 */
class EnproClassDefectDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\EnproClassDefect $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->type = $model->type;
        $this->class = $model->class;
        $this->title = $model->title;


        return $this;
    }
}
