<?php
namespace App\Contracts\CIM\SwitchInfo;

use App\Models\OldSwitchInfo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface RecloserInfoInterface extends OldSwitchInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface RecloserInfoInterface extends OldSwitchInfoInterface
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
