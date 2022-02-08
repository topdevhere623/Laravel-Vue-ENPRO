<?php
namespace App\Traits;

use App\Models\TransformerEndInfo;
use App\Models\KiloActivePower;
use App\Models\PerCent;
use App\Models\TransformerTest;
use App\Traits\TransformerTestTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait NoLoadTestTrait
 * @package App\Models\Traits
 */
trait NoLoadTestTrait
{
    use TransformerTestTrait;
    


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
    public function getExcitingCurrent() : ?PerCent
    {
        return $this->excitingCurrent()->first();
    }

    /**
     * @param PerCent $excitingCurrent
     */
    public function setExcitingCurrent(PerCent $excitingCurrent) : void
    {
        $this->excitingCurrent()->associate($excitingCurrent);
    }
    /**
     * @return TransformerTest
     */
    public function getTransformerTest() : ? TransformerTest
    {
        if($this->getNoLoadTest()->parentTransformerTest) return $this->getNoLoadTest()->parentTransformerTest;
        $this->getNoLoadTest()->parentTransformerTest = $this->TransformerTest;
        if(!$this->getNoLoadTest()->parentTransformerTest) $this->getNoLoadTest()->parentTransformerTest = new TransformerTest();
        return $this->getNoLoadTest()->parentTransformerTest;
    }

    /**
     * @param TransformerTest $TransformerTest
     */
    public function setTransformerTest(TransformerTest $TransformerTest) : void
    {
        $this->getNoLoadTest()->parentTransformerTest = $TransformerTest;
    }


}
