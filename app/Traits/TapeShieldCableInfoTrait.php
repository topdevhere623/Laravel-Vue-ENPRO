<?php
namespace App\Traits;

use App\Models\CableInfo;
use App\Traits\CableInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait TapeShieldCableInfoTrait
 * @package App\Models\Traits
 */
trait TapeShieldCableInfoTrait
{
    use CableInfoTrait;
    


    /**
     * @return CableInfo
     */
    public function getCableInfo() : ? CableInfo
    {
        if($this->getTapeShieldCableInfo()->parentCableInfo) return $this->getTapeShieldCableInfo()->parentCableInfo;
        $this->getTapeShieldCableInfo()->parentCableInfo = $this->CableInfo;
        if(!$this->getTapeShieldCableInfo()->parentCableInfo) $this->getTapeShieldCableInfo()->parentCableInfo = new CableInfo();
        return $this->getTapeShieldCableInfo()->parentCableInfo;
    }

    /**
     * @param CableInfo $CableInfo
     */
    public function setCableInfo(CableInfo $CableInfo) : void
    {
        $this->getTapeShieldCableInfo()->parentCableInfo = $CableInfo;
    }


}
