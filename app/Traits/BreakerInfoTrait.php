<?php
namespace App\Traits;

use App\Models\OldSwitchInfo;
use App\Traits\OldSwitchInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait BreakerInfoTrait
 * @package App\Models\Traits
 */
trait BreakerInfoTrait
{
    use OldSwitchInfoTrait;
    


    /**
     * @return OldSwitchInfo
     */
    public function getOldSwitchInfo() : ? OldSwitchInfo
    {
        if($this->getBreakerInfo()->parentOldSwitchInfo) return $this->getBreakerInfo()->parentOldSwitchInfo;
        $this->getBreakerInfo()->parentOldSwitchInfo = $this->OldSwitchInfo;
        if(!$this->getBreakerInfo()->parentOldSwitchInfo) $this->getBreakerInfo()->parentOldSwitchInfo = new OldSwitchInfo();
        return $this->getBreakerInfo()->parentOldSwitchInfo;
    }

    /**
     * @param OldSwitchInfo $OldSwitchInfo
     */
    public function setOldSwitchInfo(OldSwitchInfo $OldSwitchInfo) : void
    {
        $this->getBreakerInfo()->parentOldSwitchInfo = $OldSwitchInfo;
    }


}
