<?php
namespace App\Traits;

use App\Models\Length;
use App\Models\ResistancePerLength;
use App\Models\CableInfo;
use App\Traits\CableInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait ConcentricNeutralCableInfoTrait
 * @package App\Models\Traits
 */
trait ConcentricNeutralCableInfoTrait
{
    use CableInfoTrait;
    

    /**
     * @return int
     */
    public function getNeutralStrandCount() : int
    {
        return $this->getConcentricNeutralCableInfo()->neutralStrandCount;
    }

    /**
     * @param int  $neutralStrandCount
     */
    public function setNeutralStrandCount(int $neutralStrandCount) : void
    {
        $this->getConcentricNeutralCableInfo()->neutralStrandCount = $neutralStrandCount;
    }

    /**
     * @return Length|null
     */
    public function getDiameterOverNeutral() : ?Length
    {
        return $this->diameterOverNeutral()->first();
    }

    /**
     * @param Length $diameterOverNeutral
     */
    public function setDiameterOverNeutral(Length $diameterOverNeutral) : void
    {
        $this->diameterOverNeutral()->associate($diameterOverNeutral);
    }
    /**
     * @return ResistancePerLength|null
     */
    public function getNeutralStrandRDC20() : ?ResistancePerLength
    {
        return $this->neutralStrandRDC20()->first();
    }

    /**
     * @param ResistancePerLength $neutralStrandRDC20
     */
    public function setNeutralStrandRDC20(ResistancePerLength $neutralStrandRDC20) : void
    {
        $this->neutralStrandRDC20()->associate($neutralStrandRDC20);
    }
    /**
     * @return CableInfo
     */
    public function getCableInfo() : ? CableInfo
    {
        if($this->getConcentricNeutralCableInfo()->parentCableInfo) return $this->getConcentricNeutralCableInfo()->parentCableInfo;
        $this->getConcentricNeutralCableInfo()->parentCableInfo = $this->CableInfo;
        if(!$this->getConcentricNeutralCableInfo()->parentCableInfo) $this->getConcentricNeutralCableInfo()->parentCableInfo = new CableInfo();
        return $this->getConcentricNeutralCableInfo()->parentCableInfo;
    }

    /**
     * @param CableInfo $CableInfo
     */
    public function setCableInfo(CableInfo $CableInfo) : void
    {
        $this->getConcentricNeutralCableInfo()->parentCableInfo = $CableInfo;
    }


}
