<?php
namespace App\Traits;

use App\Models\BreakerConstructionKind;
use App\Models\InterrupterPositionKind;
use App\Models\Voltage;
use App\Models\Frequency;
use App\Models\CurrentFlow;
use App\Models\Seconds;
use App\Models\Pressure;
use App\Models\EnproForce;
use App\Models\Length;
use App\Models\GostClimaticModPlacementKind;
use App\Models\TemperatureRange;
use App\Models\Gost;
use App\Models\Mass;
use App\Models\AssetInfo;
use App\Traits\AssetInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait SwitchInfoTrait
 * @package App\Models\Traits
 */
trait SwitchInfoTrait
{
    use AssetInfoTrait;
    

    /**
     * @return bool
     */
    public function getIsSinglePhase() : bool
    {
        return $this->getSwitchInfo()->isSinglePhase;
    }

    /**
     * @param bool  $isSinglePhase
     */
    public function setIsSinglePhase(bool $isSinglePhase) : void
    {
        $this->getSwitchInfo()->isSinglePhase = $isSinglePhase;
    }
    /**
     * @return bool
     */
    public function getIsUnganged() : bool
    {
        return $this->getSwitchInfo()->isUnganged;
    }

    /**
     * @param bool  $isUnganged
     */
    public function setIsUnganged(bool $isUnganged) : void
    {
        $this->getSwitchInfo()->isUnganged = $isUnganged;
    }

    /**
     * @return BreakerConstructionKind|null
     */
    public function getEnproBreakerKind() : ?BreakerConstructionKind
    {
        return $this->enproBreakerKind()->first();
    }

    /**
     * @param BreakerConstructionKind $enproBreakerKind
     */
    public function setEnproBreakerKind(BreakerConstructionKind $enproBreakerKind) : void
    {
        $this->enproBreakerKind()->associate($enproBreakerKind);
    }
    /**
     * @return InterrupterPositionKind|null
     */
    public function getEnproInterrupterPosition() : ?InterrupterPositionKind
    {
        return $this->enproInterrupterPosition()->first();
    }

    /**
     * @param InterrupterPositionKind $enproInterrupterPosition
     */
    public function setEnproInterrupterPosition(InterrupterPositionKind $enproInterrupterPosition) : void
    {
        $this->enproInterrupterPosition()->associate($enproInterrupterPosition);
    }
    /**
     * @return Voltage|null
     */
    public function getRatedVoltage() : ?Voltage
    {
        return $this->ratedVoltage()->first();
    }

    /**
     * @param Voltage $ratedVoltage
     */
    public function setRatedVoltage(Voltage $ratedVoltage) : void
    {
        $this->ratedVoltage()->associate($ratedVoltage);
    }
    /**
     * @return Frequency|null
     */
    public function getRatedFrequency() : ?Frequency
    {
        return $this->ratedFrequency()->first();
    }

    /**
     * @param Frequency $ratedFrequency
     */
    public function setRatedFrequency(Frequency $ratedFrequency) : void
    {
        $this->ratedFrequency()->associate($ratedFrequency);
    }
    /**
     * @return CurrentFlow|null
     */
    public function getRatedCurrent() : ?CurrentFlow
    {
        return $this->ratedCurrent()->first();
    }

    /**
     * @param CurrentFlow $ratedCurrent
     */
    public function setRatedCurrent(CurrentFlow $ratedCurrent) : void
    {
        $this->ratedCurrent()->associate($ratedCurrent);
    }
    /**
     * @return Seconds|null
     */
    public function getRatedInterruptingTime() : ?Seconds
    {
        return $this->ratedInterruptingTime()->first();
    }

    /**
     * @param Seconds $ratedInterruptingTime
     */
    public function setRatedInterruptingTime(Seconds $ratedInterruptingTime) : void
    {
        $this->ratedInterruptingTime()->associate($ratedInterruptingTime);
    }
    /**
     * @return Pressure|null
     */
    public function getEnproRatedPressure() : ?Pressure
    {
        return $this->enproRatedPressure()->first();
    }

    /**
     * @param Pressure $enproRatedPressure
     */
    public function setEnproRatedPressure(Pressure $enproRatedPressure) : void
    {
        $this->enproRatedPressure()->associate($enproRatedPressure);
    }
    /**
     * @return EnproForce|null
     */
    public function getLowPressureLockOut() : ?EnproForce
    {
        return $this->lowPressureLockOut()->first();
    }

    /**
     * @param EnproForce $lowPressureLockOut
     */
    public function setLowPressureLockOut(EnproForce $lowPressureLockOut) : void
    {
        $this->lowPressureLockOut()->associate($lowPressureLockOut);
    }
    /**
     * @return Length|null
     */
    public function getEnproInsulationLength() : ?Length
    {
        return $this->enproInsulationLength()->first();
    }

    /**
     * @param Length $enproInsulationLength
     */
    public function setEnproInsulationLength(Length $enproInsulationLength) : void
    {
        $this->enproInsulationLength()->associate($enproInsulationLength);
    }
    /**
     * @return GostClimaticModPlacementKind|null
     */
    public function getEnproClimaticModPlacement() : ?GostClimaticModPlacementKind
    {
        return $this->enproClimaticModPlacement()->first();
    }

    /**
     * @param GostClimaticModPlacementKind $enproClimaticModPlacement
     */
    public function setEnproClimaticModPlacement(GostClimaticModPlacementKind $enproClimaticModPlacement) : void
    {
        $this->enproClimaticModPlacement()->associate($enproClimaticModPlacement);
    }
    /**
     * @return TemperatureRange|null
     */
    public function getEnproTemperatureRange() : ?TemperatureRange
    {
        return $this->enproTemperatureRange()->first();
    }

    /**
     * @param TemperatureRange $enproTemperatureRange
     */
    public function setEnproTemperatureRange(TemperatureRange $enproTemperatureRange) : void
    {
        $this->enproTemperatureRange()->associate($enproTemperatureRange);
    }
    /**
     * @return Gost|null
     */
    public function getEnproGost() : ?Gost
    {
        return $this->enproGost()->first();
    }

    /**
     * @param Gost $enproGost
     */
    public function setEnproGost(Gost $enproGost) : void
    {
        $this->enproGost()->associate($enproGost);
    }
    /**
     * @return Mass|null
     */
    public function getGasWeightPerTank() : ?Mass
    {
        return $this->gasWeightPerTank()->first();
    }

    /**
     * @param Mass $gasWeightPerTank
     */
    public function setGasWeightPerTank(Mass $gasWeightPerTank) : void
    {
        $this->gasWeightPerTank()->associate($gasWeightPerTank);
    }
    /**
     * @return AssetInfo
     */
    public function getAssetInfo() : ? AssetInfo
    {
        if($this->getSwitchInfo()->parentAssetInfo) return $this->getSwitchInfo()->parentAssetInfo;
        $this->getSwitchInfo()->parentAssetInfo = $this->AssetInfo;
        if(!$this->getSwitchInfo()->parentAssetInfo) $this->getSwitchInfo()->parentAssetInfo = new AssetInfo();
        return $this->getSwitchInfo()->parentAssetInfo;
    }

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void
    {
        $this->getSwitchInfo()->parentAssetInfo = $AssetInfo;
    }


}
