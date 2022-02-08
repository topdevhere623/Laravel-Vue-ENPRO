<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegularTimePoint
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $sequencenumber
 * @property float $value1
 * @property float $value2
 * @property int $interval_schedule
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint whereIntervalSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint whereSequencenumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint whereValue1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegularTimePoint whereValue2($value)
 * @mixin \Eloquent
 */
class RegularTimePoint extends Model
{
    //
}
