<?php


namespace App\DTO;

use App\Models\FuseInfo;

use App\DTO\OldSwitchInfoDTO;


/**
 * Class FuseInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property OldSwitchInfoDTO $OldSwitchInfo

 *
 */
class FuseInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\FuseInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        return $this;
    }
}
