<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TapChangerControl
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property float $limitvoltage
 * @property int $linedropcompensation
 * @property float $linedropr
 * @property float $linedropx
 * @property float $reverselinedropr
 * @property float $reverselinedropx
 * @property int|null $regulatingcontrols
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl query()
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereLimitvoltage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereLinedropcompensation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereLinedropr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereLinedropx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereRegulatingcontrols($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereReverselinedropr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereReverselinedropx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapChangerControl whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TapChangerControl extends Model
{
    //
}
