<?php
namespace App\Contracts\CIM\AssetInfo;

use App\Models\Length;
use App\Models\ResistancePerLength;
use App\Models\CableInfo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface ConcentricNeutralCableInfoInterface extends CableInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface ConcentricNeutralCableInfoInterface extends CableInfoInterface
{
    /**
     * @return int
     */
    public function getNeutralStrandCount() : int;

    /**
     * @param int $neutralStrandCount
     */
    public function setNeutralStrandCount(int $neutralStrandCount) : void;

    /**
     * @return Length|null
     */
    public function getDiameterOverNeutral() : ? Length;

    /**
     * @param Length $diameterOverNeutral
     */
    public function setDiameterOverNeutral(Length $diameterOverNeutral) : void;

    /**
     * @return ResistancePerLength|null
     */
    public function getNeutralStrandRDC20() : ? ResistancePerLength;

    /**
     * @param ResistancePerLength $neutralStrandRDC20
     */
    public function setNeutralStrandRDC20(ResistancePerLength $neutralStrandRDC20) : void;

    /**
     * @return CableInfo|null
     */
    public function getCableInfo() : ? CableInfo;

    /**
     * @param CableInfo $CableInfo
     */
    public function setCableInfo(CableInfo $CableInfo) : void;



}
