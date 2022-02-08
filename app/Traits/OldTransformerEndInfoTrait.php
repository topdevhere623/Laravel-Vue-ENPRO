<?php
namespace App\Traits;

use App\Models\WindingInsulationKind;
use App\Models\TransformerEndInfo;
use App\Traits\TransformerEndInfoTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait OldTransformerEndInfoTrait
 * @package App\Models\Traits
 */
trait OldTransformerEndInfoTrait
{
    use TransformerEndInfoTrait;
    


    /**
     * @return WindingInsulationKind|null
     */
    public function getWindingInsulationKind() : ?WindingInsulationKind
    {
        return $this->windingInsulationKind()->first();
    }

    /**
     * @param WindingInsulationKind $windingInsulationKind
     */
    public function setWindingInsulationKind(WindingInsulationKind $windingInsulationKind) : void
    {
        $this->windingInsulationKind()->associate($windingInsulationKind);
    }
    /**
     * @return TransformerEndInfo
     */
    public function getTransformerEndInfo() : ? TransformerEndInfo
    {
        if($this->getOldTransformerEndInfo()->parentTransformerEndInfo) return $this->getOldTransformerEndInfo()->parentTransformerEndInfo;
        $this->getOldTransformerEndInfo()->parentTransformerEndInfo = $this->TransformerEndInfo;
        if(!$this->getOldTransformerEndInfo()->parentTransformerEndInfo) $this->getOldTransformerEndInfo()->parentTransformerEndInfo = new TransformerEndInfo();
        return $this->getOldTransformerEndInfo()->parentTransformerEndInfo;
    }

    /**
     * @param TransformerEndInfo $TransformerEndInfo
     */
    public function setTransformerEndInfo(TransformerEndInfo $TransformerEndInfo) : void
    {
        $this->getOldTransformerEndInfo()->parentTransformerEndInfo = $TransformerEndInfo;
    }


}
