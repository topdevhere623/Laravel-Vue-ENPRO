<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NameType
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $name
 * @property string $description
 * @property int $nametypeauthority
 * @method static \Illuminate\Database\Eloquent\Builder|NameType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NameType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NameType query()
 * @method static \Illuminate\Database\Eloquent\Builder|NameType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NameType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NameType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NameType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NameType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NameType whereNametypeauthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NameType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NameType extends Model
{
    //
}
