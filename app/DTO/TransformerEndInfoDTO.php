<?php


namespace App\DTO;

use App\Models\TransformerEndInfo;

use App\DTO\AllDataTypeDTO;
use App\DTO\ApparentPowerDTO;
use App\DTO\VoltageDTO;
use App\DTO\ResistanceDTO;
use App\DTO\WindingInsulationKindDTO;
use App\DTO\TransformerTankInfoDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class TransformerEndInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property integer $endNumber
 * @property integer $phaseAngleClock

 * @property EnumKindDTO $connectionKind
 * @property AllDataTypeDTO $ratedS
 * @property AllDataTypeDTO $ratedU
 * @property AllDataTypeDTO $r
 * @property TransformerTankInfoDTO $TransformerTankInfo
 * @property AssetInfoDTO $AssetInfo
 * @property OldTransformerEndInfoDTO $OldTransformerEndInfo
 * @property ShortCircuitTestDTO[] $ShortCircuitTests
 * @property NoLoadTestDTO[] $NoLoadTests

 *
 */
class TransformerEndInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\TransformerEndInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadIdentifiedObject($model->AssetInfo) : null;
        $this->endNumber = $model->endNumber;
        $this->phaseAngleClock = $model->phaseAngleClock;

        $this->connectionKind = (! empty($model->connectionKind)) ? EnumKindDTO::instance()->load($model->connectionKind) : null;
        $this->ratedS = (! empty($model->ratedS)) ? AllDataTypeDTO::instance()->load($model->ratedS) : null;
        $this->ratedU = (! empty($model->ratedU)) ? AllDataTypeDTO::instance()->load($model->ratedU) : null;
        $this->r = (! empty($model->r)) ? AllDataTypeDTO::instance()->load($model->r) : null;
        $this->OldTransformerEndInfo = (! empty($model->OldTransformerEndInfo)) ? OldTransformerEndInfoDTO::instance()->load($model->OldTransformerEndInfo) : null;
        $this->ShortCircuitTests = empty($model->ShortCircuitTests) ? null : $model->ShortCircuitTests->map(function($q){
            return ShortCircuitTestDTO::instance()->load($q);
        });
        $this->NoLoadTests = empty($model->NoLoadTests) ? null : $model->NoLoadTests->map(function($q){
            return NoLoadTestDTO::instance()->load($q);
        });
        return $this;
    }

    /**
     * @param \App\Models\TransformerEndInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadShort($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadIdentifiedObject($model->AssetInfo) : null;
        return $this;
    }
}
