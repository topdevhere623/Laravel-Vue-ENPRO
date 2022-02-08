<?php
namespace App\Contracts\CIM\AssetInfo;

use App\Models\CableInfo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface TapeShieldCableInfoInterface extends CableInfoInterface
 * @package App\Contracts\CIM\Work
 */
interface TapeShieldCableInfoInterface extends CableInfoInterface
{

    /**
     * @return CableInfo|null
     */
    public function getCableInfo() : ? CableInfo;

    /**
     * @param CableInfo $CableInfo
     */
    public function setCableInfo(CableInfo $CableInfo) : void;



}
