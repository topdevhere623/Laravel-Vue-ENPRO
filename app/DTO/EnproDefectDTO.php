<?php


namespace App\DTO;

use App\Models\EnproDefect;

use App\DTO\EnproClassDefectDTO;
use App\DTO\EnproGroupDefectDTO;


/**
 * Class EnproDefectDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $code
 * @property string $title
 * @property integer $critical

 * @property EnproClassDefectDTO $enproClassDefect
 * @property EnproGroupDefectDTO $enproGroupDefect

 *
 */
class EnproDefectDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\EnproDefect $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->code = $model->code;
        $this->title = $model->title;
        $this->critical = $model->critical;

        $this->enproClassDefect = (! empty($model->enproClassDefect)) ? EnproClassDefectDTO::instance()->load($model->enproClassDefect) : null;
        $this->enproGroupDefect = (! empty($model->enproGroupDefect)) ? EnproGroupDefectDTO::instance()->load($model->enproGroupDefect) : null;

        return $this;
    }
}
