<?php


namespace App\DTO;

use App\Models\NoLoadTest;

use App\DTO\TransformerEndInfoDTO;
use App\DTO\KiloActivePowerDTO;
use App\DTO\PerCentDTO;
use App\DTO\TransformerTestDTO;


/**
 * Class NoLoadTestDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property TransformerEndInfoDTO $EnergisedEnd
 * @property AllDataTypeDTO $loss
 * @property AllDataTypeDTO $excitingCurrent
 * @property TransformerTestDTO $TransformerTest

 *
 */
class NoLoadTestDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\NoLoadTest $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;

        $this->EnergisedEnd = (! empty($model->EnergisedEnd)) ? TransformerEndInfoDTO::instance()->loadShort($model->EnergisedEnd) : null;
        $this->loss = (! empty($model->loss)) ? AllDataTypeDTO::instance()->load($model->loss) : null;
        $this->excitingCurrent = (! empty($model->excitingCurrent)) ? AllDataTypeDTO::instance()->load($model->excitingCurrent) : null;
        $this->TransformerTest = (! empty($model->TransformerTest)) ? TransformerTestDTO::instance()->load($model->TransformerTest) : null;

        return $this;
    }
}
