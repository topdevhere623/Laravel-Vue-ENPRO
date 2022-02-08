<?php
namespace App\Traits;

use App\Models\Mass;
use App\Models\CurrentFlow;
use App\Models\Seconds;
use App\Models\SecondaryCircuitsVoltageKind;
use App\Models\Voltage;
use App\Models\SwitchInfo;
use App\Traits\SwitchInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait OldSwitchInfoTrait
 * @package App\Models\Traits
 */
trait OldSwitchInfoTrait
{
    use SwitchInfoTrait;
    

    /**
     * @return bool
     */
    public function getLoadBreak() : bool
    {
        return $this->getOldSwitchInfo()->loadBreak;
    }

    /**
     * @param bool  $loadBreak
     */
    public function setLoadBreak(bool $loadBreak) : void
    {
        $this->getOldSwitchInfo()->loadBreak = $loadBreak;
    }
    /**
     * @return int
     */
    public function getPoleCount() : int
    {
        return $this->getOldSwitchInfo()->poleCount;
    }

    /**
     * @param int  $poleCount
     */
    public function setPoleCount(int $poleCount) : void
    {
        $this->getOldSwitchInfo()->poleCount = $poleCount;
    }
    /**
     * @return bool
     */
    public function getRemote() : bool
    {
        return $this->getOldSwitchInfo()->remote;
    }

    /**
     * @param bool  $remote
     */
    public function setRemote(bool $remote) : void
    {
        $this->getOldSwitchInfo()->remote = $remote;
    }

    /**
     * @return Mass|null
     */
    public function getOilVolumePerTank() : ?Mass
    {
        return $this->oilVolumePerTank()->first();
    }

    /**
     * @param Mass $oilVolumePerTank
     */
    public function setOilVolumePerTank(Mass $oilVolumePerTank) : void
    {
        $this->oilVolumePerTank()->associate($oilVolumePerTank);
    }
    /**
     * @return CurrentFlow|null
     */
    public function getWithstandCurrent() : ?CurrentFlow
    {
        return $this->withstandCurrent()->first();
    }

    /**
     * @param CurrentFlow $withstandCurrent
     */
    public function setWithstandCurrent(CurrentFlow $withstandCurrent) : void
    {
        $this->withstandCurrent()->associate($withstandCurrent);
    }
    /**
     * @return Seconds|null
     */
    public function getEnproEarthSwitchCurrentDuration() : ?Seconds
    {
        return $this->enproEarthSwitchCurrentDuration()->first();
    }

    /**
     * @param Seconds $enproEarthSwitchCurrentDuration
     */
    public function setEnproEarthSwitchCurrentDuration(Seconds $enproEarthSwitchCurrentDuration) : void
    {
        $this->enproEarthSwitchCurrentDuration()->associate($enproEarthSwitchCurrentDuration);
    }
    /**
     * @return SecondaryCircuitsVoltageKind|null
     */
    public function getEnproSecondaryVoltageKind() : ?SecondaryCircuitsVoltageKind
    {
        return $this->enproSecondaryVoltageKind()->first();
    }

    /**
     * @param SecondaryCircuitsVoltageKind $enproSecondaryVoltageKind
     */
    public function setEnproSecondaryVoltageKind(SecondaryCircuitsVoltageKind $enproSecondaryVoltageKind) : void
    {
        $this->enproSecondaryVoltageKind()->associate($enproSecondaryVoltageKind);
    }
    /**
     * @return Voltage|null
     */
    public function getEnproSecondaryVoltage() : ?Voltage
    {
        return $this->enproSecondaryVoltage()->first();
    }

    /**
     * @param Voltage $enproSecondaryVoltage
     */
    public function setEnproSecondaryVoltage(Voltage $enproSecondaryVoltage) : void
    {
        $this->enproSecondaryVoltage()->associate($enproSecondaryVoltage);
    }
    /**
     * @return SwitchInfo
     */
    public function getSwitchInfo() : ? SwitchInfo
    {
        if($this->getOldSwitchInfo()->parentSwitchInfo) return $this->getOldSwitchInfo()->parentSwitchInfo;
        $this->getOldSwitchInfo()->parentSwitchInfo = $this->SwitchInfo;
        if(!$this->getOldSwitchInfo()->parentSwitchInfo) $this->getOldSwitchInfo()->parentSwitchInfo = new SwitchInfo();
        return $this->getOldSwitchInfo()->parentSwitchInfo;
    }

    /**
     * @param SwitchInfo $SwitchInfo
     */
    public function setSwitchInfo(SwitchInfo $SwitchInfo) : void
    {
        $this->getOldSwitchInfo()->parentSwitchInfo = $SwitchInfo;
    }


}
