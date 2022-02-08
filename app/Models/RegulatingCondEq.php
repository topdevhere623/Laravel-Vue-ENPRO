<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegulatingCondEq
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $controlenabled
 * @property int $conducting_equipment_id
 * @property int|null $regulatingcontrol
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq whereConductingEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq whereControlenabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq whereRegulatingcontrol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatingCondEq whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RegulatingCondEq extends Model
{
    //
}
