<?php
namespace App\Contracts\CIM\SwitchInfo;

use App\Models\OldSwitchInfo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface BreakerInfoInterface extends OldSwitchInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface BreakerInfoInterface extends OldSwitchInfoInterface
{

    /**
     * @return OldSwitchInfo|null
     */
    public function getOldSwitchInfo() : ? OldSwitchInfo;

    /**
     * @param OldSwitchInfo $OldSwitchInfo
     */
    public function setOldSwitchInfo(OldSwitchInfo $OldSwitchInfo) : void;



}
