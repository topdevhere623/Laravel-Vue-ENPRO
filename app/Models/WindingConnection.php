<?php

namespace App\Models;

use App\Traits\KindTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WindingConnection
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $literal
 * @property string $description
 * @property int $value
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection query()
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection whereLiteral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WindingConnection whereValue($value)
 * @mixin \Eloquent
 */
class WindingConnection extends EnumKindModel
{
    protected $table  = 'winding_connections';
    //
}
