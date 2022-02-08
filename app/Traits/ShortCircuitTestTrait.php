<?php
namespace App\Traits;

use App\Models\TransformerEndInfo;
use App\Models\KiloActivePower;
use App\Models\PerCent;
use App\Models\TransformerTest;
use App\Traits\TransformerTestTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait ShortCircuitTestTrait
 * @package App\Models\Traits
 */
trait ShortCircuitTestTrait
{
    use TransformerTestTrait;
    public $GroundedEnds;



    /**
     * @return TransformerEndInfo|null
     */
    public function getEnergisedEnd() : ?TransformerEndInfo
    {
        return $this->EnergisedEnd()->first();
    }

    /**
     * @param TransformerEndInfo $EnergisedEnd
     */
    public function setEnergisedEnd(TransformerEndInfo $EnergisedEnd) : void
    {
        $this->EnergisedEnd()->associate($EnergisedEnd);
    }
    /**
     * @return KiloActivePower|null
     */
    public function getLoss() : ?KiloActivePower
    {
        return $this->loss()->first();
    }

    /**
     * @param KiloActivePower $loss
     */
    public function setLoss(KiloActivePower $loss) : void
    {
        $this->loss()->associate($loss);
    }
    /**
     * @return PerCent|null
     */
    public function getVoltage() : ?PerCent
    {
        return $this->voltage()->first();
    }

    /**
     * @param PerCent $voltage
     */
    public function setVoltage(PerCent $voltage) : void
    {
        $this->voltage()->associate($voltage);
    }
    /**
     * @return TransformerTest
     */
    public function getTransformerTest() : ? TransformerTest
    {
        if($this->getShortCircuitTest()->parentTransformerTest) return $this->getShortCircuitTest()->parentTransformerTest;
        $this->getShortCircuitTest()->parentTransformerTest = $this->TransformerTest;
        if(!$this->getShortCircuitTest()->parentTransformerTest) $this->getShortCircuitTest()->parentTransformerTest = new TransformerTest();
        return $this->getShortCircuitTest()->parentTransformerTest;
    }

    /**
     * @param TransformerTest $TransformerTest
     */
    public function setTransformerTest(TransformerTest $TransformerTest) : void
    {
        $this->getShortCircuitTest()->parentTransformerTest = $TransformerTest;
    }

    /**
     * @return array
     */
    public function getGroundedEnds() : array
    {
        if($this->getShortCircuitTest()->GroundedEnds) return $this->getShortCircuitTest()->GroundedEnds;
        $this->getShortCircuitTest()->GroundedEnds = [];
        foreach($this->getShortCircuitTest()->GroundedEnds()->get() as $relationModel) {
            $this->getShortCircuitTest()->GroundedEnds[] = $relationModel;
        };
        return $this->getShortCircuitTest()->GroundedEnds;
    }

    /**
     * @param TransformerEndInfo $GroundedEnds
     */
    public function addGroundedEnds(TransformerEndInfo $GroundedEnds) : void
    {
        $this->getShortCircuitTest()->GroundedEnds = $this->getGroundedEnds();
        if(!in_array($GroundedEnds, $this->getShortCircuitTest()->GroundedEnds)) {
            array_push($this->getShortCircuitTest()->GroundedEnds, $GroundedEnds);
        }
    }

    /**
     * @param TransformerEndInfo $GroundedEnds
     */
    public function removeGroundedEnds(TransformerEndInfo $GroundedEnds) : void
    {
        array_splice($this->getShortCircuitTest()->GroundedEnds, array_search($GroundedEnds, $this->getShortCircuitTest()->GroundedEnds ), 1);
        if($GroundedEnds->id) {
            $GroundedEnds->delete();
        }
    }


}
