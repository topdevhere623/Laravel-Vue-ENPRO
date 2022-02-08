<?php


namespace App\DTO;


use App\Models\SwitchInfo;
use App\DTO\CurrentFlowDTO;
use App\DTO\TemperatureRangeDTO;
use App\DTO\GostDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class SwitchInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property boolean $isSinglePhase
 * @property boolean $isUnganged

 * @property AllKindDTO $enproBreakerKind
 * @property AllKindDTO $enproInterrupterPosition
 * @property AllDataTypeDTO $ratedVoltage
 * @property AllDataTypeDTO $enproMaxVoltage
 * @property AllDataTypeDTO $ratedFrequency
 * @property CurrentFlowDTO $ratedCurrent
 * @property CurrentFlowDTO $breakingCapacity
 * @property AllDataTypeDTO $ratedInterruptingTime
 * @property AllDataTypeDTO $ratedImpulseWithstandVoltage
 * @property AllDataTypeDTO $enproRatedPressure
 * @property AllDataTypeDTO $lowPressureAlarm
 * @property AllDataTypeDTO $lowPressureLockOut
 * @property AllDataTypeDTO $enproBreakForce
 * @property AllDataTypeDTO $enproInsulationLength
 * @property AllKindDTO $enproClimaticModPlacement
 * @property TemperatureRangeDTO $enproTemperatureRange
 * @property GostDTO $enproGost
 * @property AllDataTypeDTO $gasWeightPerTank
 * @property AllDataTypeDTO $oilVolumePerTank
 * @property AssetInfoDTO $AssetInfo
 * @property OldSwitchInfoDTO $OldSwitchInfo

 *
 */
class SwitchInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\SwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo) : null;

        $this->isSinglePhase = $model->isSinglePhase;
        $this->isUnganged = $model->isUnganged;

        $this->enproBreakerKind = (! empty($model->enproBreakerKind)) ? AllKindDTO::instance()->load($model->enproBreakerKind) : null;
        $this->enproInterrupterPosition = (! empty($model->enproInterrupterPosition)) ? AllKindDTO::instance()->load($model->enproInterrupterPosition) : null;
        $this->ratedVoltage = (! empty($model->ratedVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedVoltage) : null;
        $this->enproMaxVoltage = (! empty($model->enproMaxVoltage)) ? AllDataTypeDTO::instance()->load($model->enproMaxVoltage) : null;
        $this->ratedFrequency = (! empty($model->ratedFrequency)) ? AllDataTypeDTO::instance()->load($model->ratedFrequency) : null;
        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? CurrentFlowDTO::instance()->load($model->ratedCurrent) : null;
        $this->breakingCapacity = (! empty($model->breakingCapacity)) ? CurrentFlowDTO::instance()->load($model->breakingCapacity) : null;
        $this->ratedInterruptingTime = (! empty($model->ratedInterruptingTime)) ? AllDataTypeDTO::instance()->load($model->ratedInterruptingTime) : null;
        $this->ratedImpulseWithstandVoltage = (! empty($model->ratedImpulseWithstandVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedImpulseWithstandVoltage) : null;
        $this->enproRatedPressure = (! empty($model->enproRatedPressure)) ? AllDataTypeDTO::instance()->load($model->enproRatedPressure) : null;
        $this->lowPressureAlarm = (! empty($model->lowPressureAlarm)) ? AllDataTypeDTO::instance()->load($model->lowPressureAlarm) : null;
        $this->lowPressureLockOut = (! empty($model->lowPressureLockOut)) ? AllDataTypeDTO::instance()->load($model->lowPressureLockOut) : null;
        $this->enproBreakForce = (! empty($model->enproBreakForce)) ? AllDataTypeDTO::instance()->load($model->enproBreakForce) : null;
        $this->enproInsulationLength = (! empty($model->enproInsulationLength)) ? AllDataTypeDTO::instance()->load($model->enproInsulationLength) : null;
        $this->enproClimaticModPlacement = (! empty($model->enproClimaticModPlacement)) ? AllKindDTO::instance()->load($model->enproClimaticModPlacement) : null;
        $this->enproTemperatureRange = (! empty($model->enproTemperatureRange)) ? TemperatureRangeDTO::instance()->load($model->enproTemperatureRange) : null;
        $this->enproGost = (! empty($model->enproGost)) ? GostDTO::instance()->load($model->enproGost) : null;
        $this->gasWeightPerTank = (! empty($model->gasWeightPerTank)) ? AllDataTypeDTO::instance()->load($model->gasWeightPerTank) : null;
        $this->oilVolumePerTank = (! empty($model->oilVolumePerTank)) ? AllDataTypeDTO::instance()->load($model->oilVolumePerTank) : null;

        $this->OldSwitchInfo = (! empty($model->OldSwitchInfo)) ? OldSwitchInfoDTO::instance()->load($model->OldSwitchInfo) : null;

        return $this;
    }

    /**
     * @param \App\Models\SwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadBreakerInfo($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo) : null;

        $this->isSinglePhase = $model->isSinglePhase;
        $this->isUnganged = $model->isUnganged;

        $this->enproBreakerKind = (! empty($model->enproBreakerKind)) ? AllKindDTO::instance()->load($model->enproBreakerKind) : null;
        $this->enproInterrupterPosition = (! empty($model->enproInterrupterPosition)) ? AllKindDTO::instance()->load($model->enproInterrupterPosition) : null;
        $this->ratedVoltage = (! empty($model->ratedVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedVoltage) : null;
        $this->enproMaxVoltage = (! empty($model->enproMaxVoltage)) ? AllDataTypeDTO::instance()->load($model->enproMaxVoltage) : null;
        $this->ratedFrequency = (! empty($model->ratedFrequency)) ? AllDataTypeDTO::instance()->load($model->ratedFrequency) : null;
        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? CurrentFlowDTO::instance()->load($model->ratedCurrent) : null;
        $this->breakingCapacity = (! empty($model->breakingCapacity)) ? CurrentFlowDTO::instance()->load($model->breakingCapacity) : null;
        $this->ratedInterruptingTime = (! empty($model->ratedInterruptingTime)) ? AllDataTypeDTO::instance()->load($model->ratedInterruptingTime) : null;
        $this->ratedImpulseWithstandVoltage = (! empty($model->ratedImpulseWithstandVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedImpulseWithstandVoltage) : null;
        $this->enproRatedPressure = (! empty($model->enproRatedPressure)) ? AllDataTypeDTO::instance()->load($model->enproRatedPressure) : null;
        $this->lowPressureAlarm = (! empty($model->lowPressureAlarm)) ? AllDataTypeDTO::instance()->load($model->lowPressureAlarm) : null;
        $this->lowPressureLockOut = (! empty($model->lowPressureLockOut)) ? AllDataTypeDTO::instance()->load($model->lowPressureLockOut) : null;
        $this->enproBreakForce = (! empty($model->enproBreakForce)) ? AllDataTypeDTO::instance()->load($model->enproBreakForce) : null;
        $this->enproInsulationLength = (! empty($model->enproInsulationLength)) ? AllDataTypeDTO::instance()->load($model->enproInsulationLength) : null;
        $this->enproClimaticModPlacement = (! empty($model->enproClimaticModPlacement)) ? AllKindDTO::instance()->load($model->enproClimaticModPlacement) : null;
        $this->enproTemperatureRange = (! empty($model->enproTemperatureRange)) ? TemperatureRangeDTO::instance()->load($model->enproTemperatureRange) : null;
        $this->enproGost = (! empty($model->enproGost)) ? GostDTO::instance()->load($model->enproGost) : null;
        $this->gasWeightPerTank = (! empty($model->gasWeightPerTank)) ? AllDataTypeDTO::instance()->load($model->gasWeightPerTank) : null;
        $this->oilVolumePerTank = (! empty($model->oilVolumePerTank)) ? AllDataTypeDTO::instance()->load($model->oilVolumePerTank) : null;

        $this->OldSwitchInfo = (! empty($model->OldSwitchInfo)) ? OldSwitchInfoDTO::instance()->loadBreakerInfo($model->OldSwitchInfo) : null;

        return $this;
    }

    /**
     * @param \App\Models\SwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadDisconnectorInfo($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo) : null;

        $this->isSinglePhase = $model->isSinglePhase;
        $this->isUnganged = $model->isUnganged;

        $this->ratedVoltage = (! empty($model->ratedVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedVoltage) : null;
        $this->enproMaxVoltage = (! empty($model->enproMaxVoltage)) ? AllDataTypeDTO::instance()->load($model->enproMaxVoltage) : null;
        $this->ratedFrequency = (! empty($model->ratedFrequency)) ? AllDataTypeDTO::instance()->load($model->ratedFrequency) : null;
        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? CurrentFlowDTO::instance()->load($model->ratedCurrent) : null;

        $this->ratedImpulseWithstandVoltage = (! empty($model->ratedImpulseWithstandVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedImpulseWithstandVoltage) : null;
        $this->enproRatedPressure = (! empty($model->enproRatedPressure)) ? AllDataTypeDTO::instance()->load($model->enproRatedPressure) : null;

        $this->enproBreakForce = (! empty($model->enproBreakForce)) ? AllDataTypeDTO::instance()->load($model->enproBreakForce) : null;
        $this->enproInsulationLength = (! empty($model->enproInsulationLength)) ? AllDataTypeDTO::instance()->load($model->enproInsulationLength) : null;
        $this->enproClimaticModPlacement = (! empty($model->enproClimaticModPlacement)) ? AllKindDTO::instance()->load($model->enproClimaticModPlacement) : null;
        $this->enproTemperatureRange = (! empty($model->enproTemperatureRange)) ? TemperatureRangeDTO::instance()->load($model->enproTemperatureRange) : null;
        $this->enproGost = (! empty($model->enproGost)) ? GostDTO::instance()->load($model->enproGost) : null;

        $this->OldSwitchInfo = (! empty($model->OldSwitchInfo)) ? OldSwitchInfoDTO::instance()->loadDisconnectorInfo($model->OldSwitchInfo) : null;

        return $this;
    }


    /**
     * @param \App\Models\SwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadLoadBreakSwitchInfo($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo) : null;

        $this->enproBreakerKind = (! empty($model->enproBreakerKind)) ? AllKindDTO::instance()->load($model->enproBreakerKind) : null;

        $this->ratedVoltage = (! empty($model->ratedVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedVoltage) : null;
        $this->enproMaxVoltage = (! empty($model->enproMaxVoltage)) ? AllDataTypeDTO::instance()->load($model->enproMaxVoltage) : null;
        $this->ratedFrequency = (! empty($model->ratedFrequency)) ? AllDataTypeDTO::instance()->load($model->ratedFrequency) : null;
        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? CurrentFlowDTO::instance()->load($model->ratedCurrent) : null;

        $this->ratedImpulseWithstandVoltage = (! empty($model->ratedImpulseWithstandVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedImpulseWithstandVoltage) : null;
        $this->enproRatedPressure = (! empty($model->enproRatedPressure)) ? AllDataTypeDTO::instance()->load($model->enproRatedPressure) : null;

        $this->enproInsulationLength = (! empty($model->enproInsulationLength)) ? AllDataTypeDTO::instance()->load($model->enproInsulationLength) : null;
        $this->enproClimaticModPlacement = (! empty($model->enproClimaticModPlacement)) ? AllKindDTO::instance()->load($model->enproClimaticModPlacement) : null;
        $this->enproTemperatureRange = (! empty($model->enproTemperatureRange)) ? TemperatureRangeDTO::instance()->load($model->enproTemperatureRange) : null;
        $this->enproGost = (! empty($model->enproGost)) ? GostDTO::instance()->load($model->enproGost) : null;
        $this->gasWeightPerTank = (! empty($model->gasWeightPerTank)) ? AllDataTypeDTO::instance()->load($model->gasWeightPerTank) : null;
        $this->oilVolumePerTank = (! empty($model->oilVolumePerTank)) ? AllDataTypeDTO::instance()->load($model->oilVolumePerTank) : null;

        $this->OldSwitchInfo = (! empty($model->OldSwitchInfo)) ? OldSwitchInfoDTO::instance()->loadLoadBreakSwitchInfo($model->OldSwitchInfo) : null;

        return $this;
    }

    /**
     * @param \App\Models\SwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadRecloserInfo($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo) : null;

        $this->enproBreakerKind = (! empty($model->enproBreakerKind)) ? AllKindDTO::instance()->load($model->enproBreakerKind) : null;

        $this->ratedVoltage = (! empty($model->ratedVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedVoltage) : null;
        $this->enproMaxVoltage = (! empty($model->enproMaxVoltage)) ? AllDataTypeDTO::instance()->load($model->enproMaxVoltage) : null;
        $this->ratedFrequency = (! empty($model->ratedFrequency)) ? AllDataTypeDTO::instance()->load($model->ratedFrequency) : null;
        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? CurrentFlowDTO::instance()->load($model->ratedCurrent) : null;

        $this->ratedImpulseWithstandVoltage = (! empty($model->ratedImpulseWithstandVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedImpulseWithstandVoltage) : null;

        $this->enproInsulationLength = (! empty($model->enproInsulationLength)) ? AllDataTypeDTO::instance()->load($model->enproInsulationLength) : null;
        $this->enproClimaticModPlacement = (! empty($model->enproClimaticModPlacement)) ? AllKindDTO::instance()->load($model->enproClimaticModPlacement) : null;
        $this->enproTemperatureRange = (! empty($model->enproTemperatureRange)) ? TemperatureRangeDTO::instance()->load($model->enproTemperatureRange) : null;
        $this->enproGost = (! empty($model->enproGost)) ? GostDTO::instance()->load($model->enproGost) : null;

        $this->OldSwitchInfo = (! empty($model->OldSwitchInfo)) ? OldSwitchInfoDTO::instance()->loadRecloserInfo($model->OldSwitchInfo) : null;

        return $this;
    }

    /**
     * @param \App\Models\SwitchInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadFuseInfo($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = (! empty($model->AssetInfo)) ? AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo) : null;

        $this->isSinglePhase = $model->isSinglePhase;

        $this->enproBreakerKind = (! empty($model->enproBreakerKind)) ? AllKindDTO::instance()->load($model->enproBreakerKind) : null;

        $this->ratedVoltage = (! empty($model->ratedVoltage)) ? AllDataTypeDTO::instance()->load($model->ratedVoltage) : null;
        $this->enproMaxVoltage = (! empty($model->enproMaxVoltage)) ? AllDataTypeDTO::instance()->load($model->enproMaxVoltage) : null;
        $this->ratedFrequency = (! empty($model->ratedFrequency)) ? AllDataTypeDTO::instance()->load($model->ratedFrequency) : null;
        $this->ratedCurrent = (! empty($model->ratedCurrent)) ? CurrentFlowDTO::instance()->load($model->ratedCurrent) : null;
        $this->breakingCapacity = (! empty($model->breakingCapacity)) ? CurrentFlowDTO::instance()->load($model->breakingCapacity) : null;

        $this->enproInsulationLength = (! empty($model->enproInsulationLength)) ? AllDataTypeDTO::instance()->load($model->enproInsulationLength) : null;
        $this->enproClimaticModPlacement = (! empty($model->enproClimaticModPlacement)) ? AllKindDTO::instance()->load($model->enproClimaticModPlacement) : null;
        $this->enproTemperatureRange = (! empty($model->enproTemperatureRange)) ? TemperatureRangeDTO::instance()->load($model->enproTemperatureRange) : null;
        $this->enproGost = (! empty($model->enproGost)) ? GostDTO::instance()->load($model->enproGost) : null;

        $this->OldSwitchInfo = (! empty($model->OldSwitchInfo)) ? OldSwitchInfoDTO::instance()->loadFuseInfo($model->OldSwitchInfo) : null;

        return $this;
    }


}
