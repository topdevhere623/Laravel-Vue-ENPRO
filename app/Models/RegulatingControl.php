<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegulatingControl
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $discrete
 * @property int $enabled
 * @property string $mode
 * @property int|null $phasecode_id
 * @property float $targetdeadband
 * @property float $targetvalue
 * @property int|null $targetvalueunitmultiplier
 * @property float $maxallowedtargetvalue
 * @property float $minallowedtargetvalue
 * @property int|null $terminal_id
 * @property int|null $power_system_resources_id
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereDiscrete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereMaxallowedtargetvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereMinallowedtargetvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl wherePhasecodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl wherePowerSystemResourcesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereTargetdeadband($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereTargetvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereTargetvalueunitmultiplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereTerminalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingControl whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RegulatingControl extends Model
{
    //
}
