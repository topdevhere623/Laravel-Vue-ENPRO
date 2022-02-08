<?php

namespace App\Models;

use App\Contracts\CIM\Wires\TransformerEndInterface;
use App\Traits\IdentifiedObjectTrait;
use App\Traits\TransformerEndTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TransformerEnd
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $identifiedobject_id
 * @property int|null $basevoltage_id
 * @property int|null $terminal_id
 * @property float $bmag_sat
 * @property int $end_number
 * @property int $grounded
 * @property float $mag_base_u
 * @property float $mag_sat_flux
 * @property float $rground
 * @property float $xground
 * @property int|null $phasetapchanger
 * @property int|null $ratiotapchanger
 * @property int|null $starimpedance
 * @property int|null $coreadmittance
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereBaseVoltageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereBmagSat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereCoreadmittance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereEndNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereGrounded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereIdentifiedobjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereMagBaseU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereMagSatFlux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd wherePhasetapchanger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereRatiotapchanger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereRground($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereStarimpedance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereTerminalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransformerEnd whereXground($value)
 * @mixin \Eloquent
 */
class TransformerEnd extends Model implements TransformerEndInterface
{
    use TransformerEndTrait;
    public $identifiedObject;

    protected $fillable = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function(TransformerEnd $model){
            $model->getIdentifiedObject()->save();
            $model->setIdentifiedObject($model->getIdentifiedObject());
        });
    }

    public function getTransformerEnd()
    {
        return $this;
    }


    //
}
