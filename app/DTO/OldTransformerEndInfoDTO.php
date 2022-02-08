<?php


namespace App\DTO;

use App\Models\OldTransformerEndInfo;

use App\DTO\AllKindDTO;
use App\DTO\TransformerEndInfoDTO;


/**
 * Class OldTransformerEndInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id

 * @property AllKindDTO $windingInsulationKind
 * @property TransformerEndInfoDTO $TransformerEndInfo

 *
 */
class OldTransformerEndInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\OldTransformerEndInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->windingInsulationKind = (! empty($model->windingInsulationKind)) ? AllKindDTO::instance()->load($model->windingInsulationKind) : null;
        return $this;
    }
}
