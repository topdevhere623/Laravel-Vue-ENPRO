<?php
namespace App\Contracts\CIM\OldTransformerEndInfo;

use App\Models\TransformerEndInfo;
use App\Models\KiloActivePower;
use App\Models\PerCent;
use App\Models\TransformerTest;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface NoLoadTestInterface extends TransformerTestInterface
 * @package App\Contracts\CIM\Work
 */
interface NoLoadTestInterface extends TransformerTestInterface
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
    public function getExcitingCurrent() : ? PerCent;

    /**
     * @param PerCent $excitingCurrent
     */
    public function setExcitingCurrent(PerCent $excitingCurrent) : void;

    /**
     * @return TransformerTest|null
     */
    public function getTransformerTest() : ? TransformerTest;

    /**
     * @param TransformerTest $TransformerTest
     */
    public function setTransformerTest(TransformerTest $TransformerTest) : void;



}
