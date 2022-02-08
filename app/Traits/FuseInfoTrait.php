<?php
namespace App\Traits;

use App\Models\OldSwitchInfo;
use App\Traits\OldSwitchInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait FuseInfoTrait
 * @package App\Models\Traits
 */
trait FuseInfoTrait
{
    use OldSwitchInfoTrait;
    


    /**
     * @return OldSwitchInfo
     */
    public function getOldSwitchInfo() : ? OldSwitchInfo
    {
        if($this->getFuseInfo()->parentOldSwitchInfo) return $this->getFuseInfo()->parentOldSwitchInfo;
        $this->getFuseInfo()->parentOldSwitchInfo = $this->OldSwitchInfo;
        if(!$this->getFuseInfo()->parentOldSwitchInfo) $this->getFuseInfo()->parentOldSwitchInfo = new OldSwitchInfo();
        return $this->getFuseInfo()->parentOldSwitchInfo;
    }

    /**
     * @param OldSwitchInfo $OldSwitchInfo
     */
    public function setOldSwitchInfo(OldSwitchInfo $OldSwitchInfo) : void
    {
        $this->getFuseInfo()->parentOldSwitchInfo = $OldSwitchInfo;
    }


}
