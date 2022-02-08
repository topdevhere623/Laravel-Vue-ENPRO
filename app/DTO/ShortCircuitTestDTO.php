<?php


namespace App\DTO;

use App\Models\ShortCircuitTest;

use App\DTO\TransformerEndInfoDTO;
use App\DTO\KiloActivePowerDTO;
use App\DTO\PerCentDTO;
use App\DTO\TransformerTestDTO;
use App\Models\TransformerEndInfo;


/**
 * Class ShortCircuitTestDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property TransformerEndInfoDTO $EnergisedEnd
 * @property TransformerEndInfoDTO[] $GroundedEnds
 * @property AllDataTypeDTO $loss
 * @property AllDataTypeDTO $voltage
 * @property TransformerTestDTO $TransformerTest
 *
 */
class ShortCircuitTestDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\ShortCircuitTest $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->EnergisedEnd = (! empty($model->EnergisedEnd)) ? TransformerEndInfoDTO::instance()->loadShort($model->EnergisedEnd) : null;
        $this->GroundedEnds = (! empty($model->GroundedEnds()->get())) ? $model->GroundedEnds()->get()->map(function ($q) {
            return TransformerEndInfoDTO::instance()->loadShort($q);
        }) : null;
        $this->loss = (! empty($model->loss)) ? AllDataTypeDTO::instance()->load($model->loss) : null;
        $this->voltage = (! empty($model->voltage)) ? AllDataTypeDTO::instance()->load($model->voltage) : null;
        $this->TransformerTest = (! empty($model->TransformerTest)) ? TransformerTestDTO::instance()->load($model->TransformerTest) : null;

        return $this;
    }
}
