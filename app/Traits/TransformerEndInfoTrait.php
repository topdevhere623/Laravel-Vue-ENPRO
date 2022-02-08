<?php
namespace App\Traits;

use App\Models\WindingConnection;
use App\Models\ApparentPower;
use App\Models\Voltage;
use App\Models\Resistance;
use App\Models\TransformerTankInfo;
use App\Models\AssetInfo;
use App\Traits\AssetInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait TransformerEndInfoTrait
 * @package App\Models\Traits
 */
trait TransformerEndInfoTrait
{
    use AssetInfoTrait;
    

    /**
     * @return int
     */
    public function getEndNumber() : int
    {
        return $this->getTransformerEndInfo()->endNumber;
    }

    /**
     * @param int  $endNumber
     */
    public function setEndNumber(int $endNumber) : void
    {
        $this->getTransformerEndInfo()->endNumber = $endNumber;
    }
    /**
     * @return int
     */
    public function getPhaseAngleClock() : int
    {
        return $this->getTransformerEndInfo()->phaseAngleClock;
    }

    /**
     * @param int  $phaseAngleClock
     */
    public function setPhaseAngleClock(int $phaseAngleClock) : void
    {
        $this->getTransformerEndInfo()->phaseAngleClock = $phaseAngleClock;
    }

    /**
     * @return WindingConnection|null
     */
    public function getConnectionKind() : ?WindingConnection
    {
        return $this->connectionKind()->first();
    }

    /**
     * @param WindingConnection $connectionKind
     */
    public function setConnectionKind(WindingConnection $connectionKind) : void
    {
        $this->connectionKind()->associate($connectionKind);
    }
    /**
     * @return ApparentPower|null
     */
    public function getRatedS() : ?ApparentPower
    {
        return $this->ratedS()->first();
    }

    /**
     * @param ApparentPower $ratedS
     */
    public function setRatedS(ApparentPower $ratedS) : void
    {
        $this->ratedS()->associate($ratedS);
    }
    /**
     * @return Voltage|null
     */
    public function getRatedU() : ?Voltage
    {
        return $this->ratedU()->first();
    }

    /**
     * @param Voltage $ratedU
     */
    public function setRatedU(Voltage $ratedU) : void
    {
        $this->ratedU()->associate($ratedU);
    }
    /**
     * @return Resistance|null
     */
    public function getR() : ?Resistance
    {
        return $this->r()->first();
    }

    /**
     * @param Resistance $r
     */
    public function setR(Resistance $r) : void
    {
        $this->r()->associate($r);
    }
    /**
     * @return TransformerTankInfo|null
     */
    public function getTransformerTankInfo() : ?TransformerTankInfo
    {
        return $this->TransformerTankInfo()->first();
    }

    /**
     * @param TransformerTankInfo $TransformerTankInfo
     */
    public function setTransformerTankInfo(TransformerTankInfo $TransformerTankInfo) : void
    {
        $this->TransformerTankInfo()->associate($TransformerTankInfo);
    }
    /**
     * @return AssetInfo
     */
    public function getAssetInfo() : ? AssetInfo
    {
        if($this->getTransformerEndInfo()->parentAssetInfo) return $this->getTransformerEndInfo()->parentAssetInfo;
        $this->getTransformerEndInfo()->parentAssetInfo = $this->AssetInfo;
        if(!$this->getTransformerEndInfo()->parentAssetInfo) $this->getTransformerEndInfo()->parentAssetInfo = new AssetInfo();
        return $this->getTransformerEndInfo()->parentAssetInfo;
    }

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void
    {
        $this->getTransformerEndInfo()->parentAssetInfo = $AssetInfo;
    }


}
