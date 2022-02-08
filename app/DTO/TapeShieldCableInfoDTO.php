<?php


namespace App\DTO;

use App\Models\TapeShieldCableInfo;

use App\DTO\CableInfoDTO;


/**
 * Class TapeShieldCableInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $name

 * @property CableInfoDTO $CableInfo

 *
 */
class TapeShieldCableInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\TapeShieldCableInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $IdentifiedObject = $model->getIdentifiedObject();
        $this->name = $IdentifiedObject->name;

        return $this;
    }

    /**
     * @param \App\Models\TapeShieldCableInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->CableInfo = (! empty($model->CableInfo)) ? CableInfoDTO::instance()->load($model->CableInfo) : null;

        return $this;
    }
}
