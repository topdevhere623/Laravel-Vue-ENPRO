<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SeasonDayTypeSchedule
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $regular_interval_schedules_id
 * @property int|null $datetype
 * @property int|null $season
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule whereDatetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule whereRegularIntervalSchedulesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule whereSeason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonDayTypeSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeasonDayTypeSchedule extends Model
{
    //
}
