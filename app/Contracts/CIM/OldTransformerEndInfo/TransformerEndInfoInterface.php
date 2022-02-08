<?php
namespace App\Contracts\CIM\OldTransformerEndInfo;

use App\Models\WindingConnection;
use App\Models\ApparentPower;
use App\Models\Voltage;
use App\Models\Resistance;
use App\Models\TransformerTankInfo;
use App\Models\AssetInfo;
use App\Contracts\CIM\AssetInfo\AssetInfoInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface TransformerEndInfoInterface extends AssetInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface TransformerEndInfoInterface extends AssetInfoInterface
{
    /**
     * @return int
     */
    public function getEndNumber() : int;

    /**
     * @param int $endNumber
     */
    public function setEndNumber(int $endNumber) : void;
    /**
     * @return int
     */
    public function getPhaseAngleClock() : int;

    /**
     * @param int $phaseAngleClock
     */
    public function setPhaseAngleClock(int $phaseAngleClock) : void;

    /**
     * @return WindingConnection|null
     */
    public function getConnectionKind() : ? WindingConnection;

    /**
     * @param WindingConnection $connectionKind
     */
    public function setConnectionKind(WindingConnection $connectionKind) : void;

    /**
     * @return ApparentPower|null
     */
    public function getRatedS() : ? ApparentPower;

    /**
     * @param ApparentPower $ratedS
     */
    public function setRatedS(ApparentPower $ratedS) : void;

    /**
     * @return Voltage|null
     */
    public function getRatedU() : ? Voltage;

    /**
     * @param Voltage $ratedU
     */
    public function setRatedU(Voltage $ratedU) : void;

    /**
     * @return Resistance|null
     */
    public function getR() : ? Resistance;

    /**
     * @param Resistance $r
     */
    public function setR(Resistance $r) : void;

    /**
     * @return TransformerTankInfo|null
     */
    public function getTransformerTankInfo() : ? TransformerTankInfo;

    /**
     * @param TransformerTankInfo $TransformerTankInfo
     */
    public function setTransformerTankInfo(TransformerTankInfo $TransformerTankInfo) : void;

    /**
     * @return AssetInfo|null
     */
    public function getAssetInfo() : ? AssetInfo;

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void;



}
