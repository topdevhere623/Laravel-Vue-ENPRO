<?php


namespace App\DTO;

use App\Models\LoadBreakSwitchInfo;

use App\DTO\OldSwitchInfoDTO;


/**
 * Class LoadBreakSwitchInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property OldSwitchInfoDTO $OldSwitchInfo

 *
 */
class LoadBreakSwitchInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\LoadBreakSwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        return $this;
    }
}
