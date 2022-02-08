<?php


namespace App\DTO;

use App\Models\OldSwitchInfo;

use App\DTO\CurrentFlowDTO;
use App\DTO\SwitchInfoDTO;


/**
 * Class OldSwitchInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property boolean $loadBreak
 * @property integer $poleCount
 * @property boolean $remote

 * @property AllDataTypeDTO $oilVolumePerTank
 * @property CurrentFlowDTO $withstandCurrent
 * @property AllDataTypeDTO $enproEarthSwitchCurrentDuration
 * @property AllKindDTO $enproSecondaryVoltageKind
 * @property AllDataTypeDTO $enproSecondaryVoltage
 * @property AllDataTypeDTO $makingCapacity
 * @property AllDataTypeDTO $enproWithstandCurrentDuration
 * @property BreakerInfoDTO $BreakerInfo
 * @property RecloserInfoDTO $RecloserInfo
 * @property FuseInfoDTO $FuseInfo
 * @property DisconnectorInfoDTO $DisconnectorInfo
 * @property LoadBreakSwitchInfoDTO $LoadBreakSwitchInfo

 *
 */
class OldSwitchInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\OldSwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->loadBreak = $model->loadBreak;
        $this->poleCount = $model->poleCount;
        $this->remote = $model->remote;

        $this->withstandCurrent = (! empty($model->withstandCurrent)) ? CurrentFlowDTO::instance()->load($model->withstandCurrent) : null;
        $this->enproEarthSwitchCurrentDuration = (! empty($model->enproEarthSwitchCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproEarthSwitchCurrentDuration) : null;
        $this->enproSecondaryVoltageKind = (! empty($model->enproSecondaryVoltageKind)) ? AllKindDTO::instance()->load($model->enproSecondaryVoltageKind) : null;
        $this->enproSecondaryVoltage = (! empty($model->enproSecondaryVoltage)) ? AllDataTypeDTO::instance()->load($model->enproSecondaryVoltage) : null;
        $this->makingCapacity = (! empty($model->makingCapacity)) ? AllDataTypeDTO::instance()->load($model->makingCapacity) : null;
        $this->enproWithstandCurrentDuration = (! empty($model->enproWithstandCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproWithstandCurrentDuration) : null;

        $this->BreakerInfo = (! empty($model->BreakerInfo)) ? BreakerInfoDTO::instance()->load($model->BreakerInfo) : null;
        $this->RecloserInfo = (! empty($model->RecloserInfo)) ? RecloserInfoDTO::instance()->load($model->RecloserInfo) : null;
        $this->FuseInfo = (! empty($model->FuseInfo)) ? FuseInfoDTO::instance()->load($model->FuseInfo) : null;
        $this->DisconnectorInfo = (! empty($model->DisconnectorInfo)) ? DisconnectorInfoDTO::instance()->load($model->DisconnectorInfo) : null;
        $this->LoadBreakSwitchInfo = (! empty($model->LoadBreakSwitchInfo)) ? LoadBreakSwitchInfoDTO::instance()->load($model->LoadBreakSwitchInfo) : null;

        return $this;
    }

    /**
     * @param \App\Models\OldSwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadBreakerInfo($model)
    {
        $this->id = $model->id;
        $this->poleCount = $model->poleCount;
        $this->remote = $model->remote;

        $this->withstandCurrent = (! empty($model->withstandCurrent)) ? CurrentFlowDTO::instance()->load($model->withstandCurrent) : null;
        $this->enproSecondaryVoltageKind = (! empty($model->enproSecondaryVoltageKind)) ? AllKindDTO::instance()->load($model->enproSecondaryVoltageKind) : null;
        $this->enproSecondaryVoltage = (! empty($model->enproSecondaryVoltage)) ? AllDataTypeDTO::instance()->load($model->enproSecondaryVoltage) : null;
        $this->makingCapacity = (! empty($model->makingCapacity)) ? AllDataTypeDTO::instance()->load($model->makingCapacity) : null;
        $this->enproWithstandCurrentDuration = (! empty($model->enproWithstandCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproWithstandCurrentDuration) : null;

        $this->BreakerInfo = (! empty($model->BreakerInfo)) ? BreakerInfoDTO::instance()->load($model->BreakerInfo) : null;

        return $this;
    }

    /**
     * @param \App\Models\OldSwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadDisconnectorInfo($model)
    {
        $this->id = $model->id;

        $this->poleCount = $model->poleCount;
        $this->remote = $model->remote;

        $this->withstandCurrent = (! empty($model->withstandCurrent)) ? CurrentFlowDTO::instance()->load($model->withstandCurrent) : null;
        $this->enproEarthSwitchCurrentDuration = (! empty($model->enproEarthSwitchCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproEarthSwitchCurrentDuration) : null;
        $this->enproSecondaryVoltageKind = (! empty($model->enproSecondaryVoltageKind)) ? AllKindDTO::instance()->load($model->enproSecondaryVoltageKind) : null;
        $this->enproSecondaryVoltage = (! empty($model->enproSecondaryVoltage)) ? AllDataTypeDTO::instance()->load($model->enproSecondaryVoltage) : null;
        $this->makingCapacity = (! empty($model->makingCapacity)) ? AllDataTypeDTO::instance()->load($model->makingCapacity) : null;
        $this->enproWithstandCurrentDuration = (! empty($model->enproWithstandCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproWithstandCurrentDuration) : null;

        $this->DisconnectorInfo = (! empty($model->DisconnectorInfo)) ? DisconnectorInfoDTO::instance()->load($model->DisconnectorInfo) : null;

        return $this;
    }


    /**
     * @param \App\Models\OldSwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadLoadBreakSwitchInfo($model)
    {
        $this->id = $model->id;
        $this->loadBreak = $model->loadBreak;
        $this->poleCount = $model->poleCount;
        $this->remote = $model->remote;

        $this->withstandCurrent = (! empty($model->withstandCurrent)) ? CurrentFlowDTO::instance()->load($model->withstandCurrent) : null;
        $this->enproEarthSwitchCurrentDuration = (! empty($model->enproEarthSwitchCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproEarthSwitchCurrentDuration) : null;
        $this->enproSecondaryVoltageKind = (! empty($model->enproSecondaryVoltageKind)) ? AllKindDTO::instance()->load($model->enproSecondaryVoltageKind) : null;
        $this->enproSecondaryVoltage = (! empty($model->enproSecondaryVoltage)) ? AllDataTypeDTO::instance()->load($model->enproSecondaryVoltage) : null;
        $this->makingCapacity = (! empty($model->makingCapacity)) ? AllDataTypeDTO::instance()->load($model->makingCapacity) : null;
        $this->enproWithstandCurrentDuration = (! empty($model->enproWithstandCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproWithstandCurrentDuration) : null;

        $this->LoadBreakSwitchInfo = (! empty($model->LoadBreakSwitchInfo)) ? LoadBreakSwitchInfoDTO::instance()->load($model->LoadBreakSwitchInfo) : null;

        return $this;
    }

    /**
     * @param \App\Models\OldSwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadRecloserInfo($model)
    {
        $this->id = $model->id;
        $this->poleCount = $model->poleCount;
        $this->remote = $model->remote;

        $this->withstandCurrent = (! empty($model->withstandCurrent)) ? CurrentFlowDTO::instance()->load($model->withstandCurrent) : null;
        $this->enproEarthSwitchCurrentDuration = (! empty($model->enproEarthSwitchCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproEarthSwitchCurrentDuration) : null;
        $this->enproSecondaryVoltageKind = (! empty($model->enproSecondaryVoltageKind)) ? AllKindDTO::instance()->load($model->enproSecondaryVoltageKind) : null;
        $this->enproSecondaryVoltage = (! empty($model->enproSecondaryVoltage)) ? AllDataTypeDTO::instance()->load($model->enproSecondaryVoltage) : null;
        $this->makingCapacity = (! empty($model->makingCapacity)) ? AllDataTypeDTO::instance()->load($model->makingCapacity) : null;
        $this->enproWithstandCurrentDuration = (! empty($model->enproWithstandCurrentDuration)) ? AllDataTypeDTO::instance()->load($model->enproWithstandCurrentDuration) : null;

        $this->RecloserInfo = (! empty($model->RecloserInfo)) ? RecloserInfoDTO::instance()->load($model->RecloserInfo) : null;

        return $this;
    }

    /**
     * @param \App\Models\OldSwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadFuseInfo($model)
    {
        $this->id = $model->id;

        $this->poleCount = $model->poleCount;

        $this->FuseInfo = (! empty($model->FuseInfo)) ? FuseInfoDTO::instance()->load($model->FuseInfo) : null;

        return $this;
    }

}
