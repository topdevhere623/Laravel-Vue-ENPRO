<?php


namespace App\DTO;

use App\Models\Ratio;

class RatioDTO extends AbstractPublicDTO
{

    /**
     * @param Ratio $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->denominator = $model->denominator;
        $this->numerator = $model->numerator;
        return $this;
    }

    /**
     * @param Ratio $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        return $this;
    }
}
