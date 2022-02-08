<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

/**
 * @property integer $id
 * @property string $name
 * @property string $keylink
 * @property string $classname
 * @property string $marktypekey
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Identifiedobject[] $identifiedobjects
 * @property Subclass[] $subclasses
 */

class Classname extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    /**
     * управляемая таблица
     *
     * @var string
     */
    protected $table = "classname";

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * список полей, разрешенных на редактирование
     * @var array
     */
    protected $fillable = ['name', 'keylink', 'classname', 'marktypekey'];

    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Имя класса";
    const title2 = "Имена классов";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/classname/' . $this->id;
    }

    // связи

    /**
     * @return HasMany
     */
    public function identifiedobjects()
    {
        return $this->hasMany('App\Models\Identifiedobject');
    }

    /**
     * @return HasMany
     */
    public function subclasses()
    {
        return $this->hasMany('App\Models\Subclass');
    }
}
