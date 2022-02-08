<?php
namespace App\Contracts\CIM\SwitchInfo;

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
use App\Contracts\CIM\AssetInfo\AssetInfoInterface;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface SwitchInfoInterface extends AssetInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface SwitchInfoInterface extends AssetInfoInterface
{
    /**
     * @return bool
     */
    public function getIsSinglePhase() : bool;

    /**
     * @param bool $isSinglePhase
     */
    public function setIsSinglePhase(bool $isSinglePhase) : void;
    /**
     * @return bool
     */
    public function getIsUnganged() : bool;

    /**
     * @param bool $isUnganged
     */
    public function setIsUnganged(bool $isUnganged) : void;

    /**
     * @return BreakerConstructionKind|null
     */
    public function getEnproBreakerKind() : ? BreakerConstructionKind;

    /**
     * @param BreakerConstructionKind $enproBreakerKind
     */
    public function setEnproBreakerKind(BreakerConstructionKind $enproBreakerKind) : void;

    /**
     * @return InterrupterPositionKind|null
     */
    public function getEnproInterrupterPosition() : ? InterrupterPositionKind;

    /**
     * @param InterrupterPositionKind $enproInterrupterPosition
     */
    public function setEnproInterrupterPosition(InterrupterPositionKind $enproInterrupterPosition) : void;

    /**
     * @return Voltage|null
     */
    public function getRatedVoltage() : ? Voltage;

    /**
     * @param Voltage $ratedVoltage
     */
    public function setRatedVoltage(Voltage $ratedVoltage) : void;

    /**
     * @return Frequency|null
     */
    public function getRatedFrequency() : ? Frequency;

    /**
     * @param Frequency $ratedFrequency
     */
    public function setRatedFrequency(Frequency $ratedFrequency) : void;

    /**
     * @return CurrentFlow|null
     */
    public function getRatedCurrent() : ? CurrentFlow;

    /**
     * @param CurrentFlow $ratedCurrent
     */
    public function setRatedCurrent(CurrentFlow $ratedCurrent) : void;

    /**
     * @return Seconds|null
     */
    public function getRatedInterruptingTime() : ? Seconds;

    /**
     * @param Seconds $ratedInterruptingTime
     */
    public function setRatedInterruptingTime(Seconds $ratedInterruptingTime) : void;

    /**
     * @return Pressure|null
     */
    public function getEnproRatedPressure() : ? Pressure;

    /**
     * @param Pressure $enproRatedPressure
     */
    public function setEnproRatedPressure(Pressure $enproRatedPressure) : void;

    /**
     * @return EnproForce|null
     */
    public function getLowPressureLockOut() : ? EnproForce;

    /**
     * @param EnproForce $lowPressureLockOut
     */
    public function setLowPressureLockOut(EnproForce $lowPressureLockOut) : void;

    /**
     * @return Length|null
     */
    public function getEnproInsulationLength() : ? Length;

    /**
     * @param Length $enproInsulationLength
     */
    public function setEnproInsulationLength(Length $enproInsulationLength) : void;

    /**
     * @return GostClimaticModPlacementKind|null
     */
    public function getEnproClimaticModPlacement() : ? GostClimaticModPlacementKind;

    /**
     * @param GostClimaticModPlacementKind $enproClimaticModPlacement
     */
    public function setEnproClimaticModPlacement(GostClimaticModPlacementKind $enproClimaticModPlacement) : void;

    /**
     * @return TemperatureRange|null
     */
    public function getEnproTemperatureRange() : ? TemperatureRange;

    /**
     * @param TemperatureRange $enproTemperatureRange
     */
    public function setEnproTemperatureRange(TemperatureRange $enproTemperatureRange) : void;

    /**
     * @return Gost|null
     */
    public function getEnproGost() : ? Gost;

    /**
     * @param Gost $enproGost
     */
    public function setEnproGost(Gost $enproGost) : void;

    /**
     * @return Mass|null
     */
    public function getGasWeightPerTank() : ? Mass;

    /**
     * @param Mass $gasWeightPerTank
     */
    public function setGasWeightPerTank(Mass $gasWeightPerTank) : void;

    /**
     * @return AssetInfo|null
     */
    public function getAssetInfo() : ? AssetInfo;

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void;



}
