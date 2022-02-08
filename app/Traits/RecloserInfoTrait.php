<?php
namespace App\Traits;

use App\Models\OldSwitchInfo;
use App\Traits\OldSwitchInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait RecloserInfoTrait
 * @package App\Models\Traits
 */
trait RecloserInfoTrait
{
    use OldSwitchInfoTrait;
    


    /**
     * @return OldSwitchInfo
     */
    public function getOldSwitchInfo() : ? OldSwitchInfo
    {
        if($this->getRecloserInfo()->parentOldSwitchInfo) return $this->getRecloserInfo()->parentOldSwitchInfo;
        $this->getRecloserInfo()->parentOldSwitchInfo = $this->OldSwitchInfo;
        if(!$this->getRecloserInfo()->parentOldSwitchInfo) $this->getRecloserInfo()->parentOldSwitchInfo = new OldSwitchInfo();
        return $this->getRecloserInfo()->parentOldSwitchInfo;
    }

    /**
     * @param OldSwitchInfo $OldSwitchInfo
     */
    public function setOldSwitchInfo(OldSwitchInfo $OldSwitchInfo) : void
    {
        $this->getRecloserInfo()->parentOldSwitchInfo = $OldSwitchInfo;
    }


}
