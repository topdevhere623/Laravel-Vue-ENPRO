<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RatioTapChanger
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property float $stepvoltageincrement
 * @property string $tculcontrolmode
 * @property int|null $tap_changers_id
 * @property int|null $ratiotapchangertable
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger query()
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger whereRatiotapchangertable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger whereStepvoltageincrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger whereTapChangersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger whereTculcontrolmode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatioTapChanger whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RatioTapChanger extends Model
{
    //
}
