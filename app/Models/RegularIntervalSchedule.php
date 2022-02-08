<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegularIntervalSchedule
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $endtime
 * @property float $timestep
 * @property int $regular_interval_schedules_id
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule whereEndtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule whereRegularIntervalSchedulesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule whereTimestep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularIntervalSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RegularIntervalSchedule extends Model
{
    //
}
