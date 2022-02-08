<?php


namespace App\DTO;

use App\Models\RecloserInfo;

use App\DTO\OldSwitchInfoDTO;


/**
 * Class RecloserInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property OldSwitchInfoDTO $OldSwitchInfo

 *
 */
class RecloserInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\RecloserInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;

        return $this;
    }
}
