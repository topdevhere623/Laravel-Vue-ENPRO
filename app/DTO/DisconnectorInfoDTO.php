<?php


namespace App\DTO;

use App\Models\DisconnectorInfo;

use App\DTO\OldSwitchInfoDTO;


/**
 * Class DisconnectorInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property OldSwitchInfoDTO $OldSwitchInfo

 *
 */
class DisconnectorInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\DisconnectorInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        return $this;
    }
}
