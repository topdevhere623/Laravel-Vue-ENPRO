<?php
namespace App\Contracts\CIM\OldTransformerEndInfo;

use App\Models\TransformerEndInfo;
use App\Models\KiloActivePower;
use App\Models\PerCent;
use App\Models\TransformerTest;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface ShortCircuitTestInterface extends TransformerTestInterface
 * @package App\Contracts\CIM\Work
 */
interface ShortCircuitTestInterface extends TransformerTestInterface
{

    /**
     * @return TransformerEndInfo|null
     */
    public function getEnergisedEnd() : ? TransformerEndInfo;

    /**
     * @param TransformerEndInfo $EnergisedEnd
     */
    public function setEnergisedEnd(TransformerEndInfo $EnergisedEnd) : void;

    /**
     * @return KiloActivePower|null
     */
    public function getLoss() : ? KiloActivePower;

    /**
     * @param KiloActivePower $loss
     */
    public function setLoss(KiloActivePower $loss) : void;

    /**
     * @return PerCent|null
     */
    public function getVoltage() : ? PerCent;

    /**
     * @param PerCent $voltage
     */
    public function setVoltage(PerCent $voltage) : void;

    /**
     * @return TransformerTest|null
     */
    public function getTransformerTest() : ? TransformerTest;

    /**
     * @param TransformerTest $TransformerTest
     */
    public function setTransformerTest(TransformerTest $TransformerTest) : void;


    /**
     * @return array
     */
    public function getGroundedEnds() : array;

    /**
     * @param TransformerEndInfo $GroundedEnds
     */
    public function addGroundedEnds(TransformerEndInfo $GroundedEnds) : void;

    /**
     * @param TransformerEndInfo $GroundedEnds
     */
    public function removeGroundedEnds(TransformerEndInfo $GroundedEnds) : void;


}
