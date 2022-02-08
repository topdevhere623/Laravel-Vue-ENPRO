<?php

namespace App\Models;

use App\Models\BaseModel as BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DocumentTrait;
use App\Contracts\CIM\Asset\DocumentInterface as DocumentInterface;

// трайты
use App\Traits\CommonTrait;

// модели

/**
 * @property integer $id
 * @property integer $identifiedobject_id
 * @property \App\Models\Identifiedobject $IdentifiedObject
 */
// модель
class Document extends BaseModel implements DocumentInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use DocumentTrait;
    public $parentIdentifiedObject = null;

    // управляемая таблица
    protected $table = "document";

    /**
     * список полей, разрешенных на редактирование
     * @var array
     */
    protected $fillable = [
        'identifiedobject_id',
        'authorName',
        'comment',
        'createdDateTime',
        'lastModifiedDateTime',
        'revisionNumber',
        'subject',
        'title',
        'type',
        'author',
        'editor'
    ];

    /**
     * Date Fields format date and time
     * @var string[]
     */
    protected $casts = [
        'identifiedobject_id' => 'integer',
    ];

    /**
     * список полей запрещенных на редактирование
     * @var array
     */
    protected $guarded = [];

    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    // мои атрибуты модели
    const title1 = "Документ";
    const title2 = "Документ";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/asset/' . $this->id;
    }

    // связи

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function IdentifiedObject()
    {
        return $this->belongsTo(\App\Models\Identifiedobject::class, 'identifiedobject_id');
    }


    /**
     * @return array
     */
    public function Names(): array
    {
        return $this->IdentifiedObject->Names;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Document $model) {
            $IdentifiedObject = $model->getIdentifiedObject();
            if (! empty($IdentifiedObject)) {
                $IdentifiedObject->save();
                $model->IdentifiedObject()->associate($IdentifiedObject);
            };
        });

    }

    public function getDocument() : Document
    {
        return $this;
    }
}
