<?php


namespace App\DTO;

use App\Models\BreakerInfo;

use App\DTO\OldSwitchInfoDTO;


/**
 * Class BreakerInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property OldSwitchInfoDTO $OldSwitchInfo

 *
 */
class BreakerInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\BreakerInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        return $this;
    }
}
