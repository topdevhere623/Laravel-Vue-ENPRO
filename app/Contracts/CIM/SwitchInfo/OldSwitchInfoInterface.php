<?php
namespace App\Contracts\CIM\SwitchInfo;

use App\Models\Mass;
use App\Models\CurrentFlow;
use App\Models\Seconds;
use App\Models\SecondaryCircuitsVoltageKind;
use App\Models\Voltage;
use App\Models\SwitchInfo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface OldSwitchInfoInterface extends SwitchInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface OldSwitchInfoInterface extends SwitchInfoInterface
{
    /**
     * @return bool
     */
    public function getLoadBreak() : bool;

    /**
     * @param bool $loadBreak
     */
    public function setLoadBreak(bool $loadBreak) : void;
    /**
     * @return int
     */
    public function getPoleCount() : int;

    /**
     * @param int $poleCount
     */
    public function setPoleCount(int $poleCount) : void;
    /**
     * @return bool
     */
    public function getRemote() : bool;

    /**
     * @param bool $remote
     */
    public function setRemote(bool $remote) : void;

    /**
     * @return Mass|null
     */
    public function getOilVolumePerTank() : ? Mass;

    /**
     * @param Mass $oilVolumePerTank
     */
    public function setOilVolumePerTank(Mass $oilVolumePerTank) : void;

    /**
     * @return CurrentFlow|null
     */
    public function getWithstandCurrent() : ? CurrentFlow;

    /**
     * @param CurrentFlow $withstandCurrent
     */
    public function setWithstandCurrent(CurrentFlow $withstandCurrent) : void;

    /**
     * @return Seconds|null
     */
    public function getEnproEarthSwitchCurrentDuration() : ? Seconds;

    /**
     * @param Seconds $enproEarthSwitchCurrentDuration
     */
    public function setEnproEarthSwitchCurrentDuration(Seconds $enproEarthSwitchCurrentDuration) : void;

    /**
     * @return SecondaryCircuitsVoltageKind|null
     */
    public function getEnproSecondaryVoltageKind() : ? SecondaryCircuitsVoltageKind;

    /**
     * @param SecondaryCircuitsVoltageKind $enproSecondaryVoltageKind
     */
    public function setEnproSecondaryVoltageKind(SecondaryCircuitsVoltageKind $enproSecondaryVoltageKind) : void;

    /**
     * @return Voltage|null
     */
    public function getEnproSecondaryVoltage() : ? Voltage;

    /**
     * @param Voltage $enproSecondaryVoltage
     */
    public function setEnproSecondaryVoltage(Voltage $enproSecondaryVoltage) : void;

    /**
     * @return SwitchInfo|null
     */
    public function getSwitchInfo() : ? SwitchInfo;

    /**
     * @param SwitchInfo $SwitchInfo
     */
    public function setSwitchInfo(SwitchInfo $SwitchInfo) : void;



}
