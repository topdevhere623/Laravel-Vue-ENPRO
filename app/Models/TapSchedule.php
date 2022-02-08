<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TapSchedule
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $season
 * @property int|null $tapchanger
 * @property int|null $daytype
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule whereDaytype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule whereSeason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule whereTapchanger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TapSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TapSchedule extends Model
{
    //
}
