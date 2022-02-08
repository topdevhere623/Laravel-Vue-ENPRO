<?php
namespace App\Traits;

use App\Models\OldSwitchInfo;
use App\Traits\OldSwitchInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait LoadBreakSwitchInfoTrait
 * @package App\Models\Traits
 */
trait LoadBreakSwitchInfoTrait
{
    use OldSwitchInfoTrait;
    


    /**
     * @return OldSwitchInfo
     */
    public function getOldSwitchInfo() : ? OldSwitchInfo
    {
        if($this->getLoadBreakSwitchInfo()->parentOldSwitchInfo) return $this->getLoadBreakSwitchInfo()->parentOldSwitchInfo;
        $this->getLoadBreakSwitchInfo()->parentOldSwitchInfo = $this->OldSwitchInfo;
        if(!$this->getLoadBreakSwitchInfo()->parentOldSwitchInfo) $this->getLoadBreakSwitchInfo()->parentOldSwitchInfo = new OldSwitchInfo();
        return $this->getLoadBreakSwitchInfo()->parentOldSwitchInfo;
    }

    /**
     * @param OldSwitchInfo $OldSwitchInfo
     */
    public function setOldSwitchInfo(OldSwitchInfo $OldSwitchInfo) : void
    {
        $this->getLoadBreakSwitchInfo()->parentOldSwitchInfo = $OldSwitchInfo;
    }


}
