<?php
namespace App\Traits;

use App\Models\OldSwitchInfo;
use App\Traits\OldSwitchInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait DisconnectorInfoTrait
 * @package App\Models\Traits
 */
trait DisconnectorInfoTrait
{
    use OldSwitchInfoTrait;
    


    /**
     * @return OldSwitchInfo
     */
    public function getOldSwitchInfo() : ? OldSwitchInfo
    {
        if($this->getDisconnectorInfo()->parentOldSwitchInfo) return $this->getDisconnectorInfo()->parentOldSwitchInfo;
        $this->getDisconnectorInfo()->parentOldSwitchInfo = $this->OldSwitchInfo;
        if(!$this->getDisconnectorInfo()->parentOldSwitchInfo) $this->getDisconnectorInfo()->parentOldSwitchInfo = new OldSwitchInfo();
        return $this->getDisconnectorInfo()->parentOldSwitchInfo;
    }

    /**
     * @param OldSwitchInfo $OldSwitchInfo
     */
    public function setOldSwitchInfo(OldSwitchInfo $OldSwitchInfo) : void
    {
        $this->getDisconnectorInfo()->parentOldSwitchInfo = $OldSwitchInfo;
    }


}
