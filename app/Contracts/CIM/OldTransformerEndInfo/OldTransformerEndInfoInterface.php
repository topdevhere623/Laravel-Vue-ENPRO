<?php
namespace App\Contracts\CIM\OldTransformerEndInfo;

use App\Models\WindingInsulationKind;
use App\Models\TransformerEndInfo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface OldTransformerEndInfoInterface extends TransformerEndInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface OldTransformerEndInfoInterface extends TransformerEndInfoInterface
{

    /**
     * @return WindingInsulationKind|null
     */
    public function getWindingInsulationKind() : ? WindingInsulationKind;

    /**
     * @param WindingInsulationKind $windingInsulationKind
     */
    public function setWindingInsulationKind(WindingInsulationKind $windingInsulationKind) : void;

    /**
     * @return TransformerEndInfo|null
     */
    public function getTransformerEndInfo() : ? TransformerEndInfo;

    /**
     * @param TransformerEndInfo $TransformerEndInfo
     */
    public function setTransformerEndInfo(TransformerEndInfo $TransformerEndInfo) : void;



}
