<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BasicIntervalSchedule
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $starttime
 * @property string $value1multiplier
 * @property string $value2multiplier
 * @property string $value1unit
 * @property string $value2unit
 * @property int $identifiedobject_id
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereIdentifiedobjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereStarttime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereValue1multiplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereValue1unit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereValue2multiplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BasicIntervalSchedule whereValue2unit($value)
 * @mixin \Eloquent
 */
class BasicIntervalSchedule extends Model
{
    //
}
